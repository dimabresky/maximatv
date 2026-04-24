# План: выбор качества видео (Video.js)

## Контекст

- Плеер: Video.js **7.6.6** из `[local/templates/MaximaTV/js/videojs/video.js](local/templates/MaximaTV/js/videojs/video.js)`, стили `[local/templates/MaximaTV/css/videojs/video-js.css](local/templates/MaximaTV/css/videojs/video-js.css)`, hotkeys `[videojs.hotkeys.min.js](local/templates/MaximaTV/js/videojs/videojs.hotkeys.min.js)`.
- Сейчас везде один `<source src="...">` и `videojs('MaximaTV-video')` + hotkeys (пример: `[local/templates/MaximaTV/components/bitrix/news/events/bitrix/news.detail/.default/template.php](local/templates/MaximaTV/components/bitrix/news/events/bitrix/news.detail/.default/template.php)` строки 91–118).
- Два способа получения URL основного файла:
  - `**VIDEO_FILE`** — готовый URL в свойстве (`.default` для `events` / `events.v2`).
  - `**PATH_TO_VIDEO` + scandir** — относительный путь к каталогу, выбор файла по индексу `VNUM` (`[master-class/template.php](local/templates/MaximaTV/components/bitrix/news/events/bitrix/news.detail/master-class/template.php)`, `[catalog.section/competitions/template.php](local/templates/MaximaTV/components/bitrix/catalog.section/competitions/template.php)`).

Правило файлов: рядом с основным файлом `.../dir/{basename}` лежит каталог `.../dir/ext/`. Внутри `ext/` ищем **все** файлы, имя которых соответствует шаблону `{префикс}_{basename}`, где `basename` — **точное** имя исходного файла (включая расширение). Префикс задаёт метку качества (например `480`, `720`, `360` или другое значение — **не фиксируем список**). Исходный файл по свойству считается максимальным качеством (условно **1080p** / «оригинал» в UI).

**Размещение общей логики:** вся серверная логика — только в `[local/php_interface/lib/Helpers](local/php_interface/lib/Helpers)` (например `VideoQualityHelper.php`, `Maxima\Helpers`); шаблоны только вызывают хелпер. Клиент (Video.js, плагин, hotkeys) — в шаблоне/include/JS.

```mermaid
flowchart LR
  webPath[Web path to source file]
  helper[VideoQualityHelper PHP]
  fs[scandir ext plus basename match]
  json[Sources JSON for player]
  webPath --> helper
  helper --> fs
  fs --> json
```



## Серверная часть

1. Добавить хелпер `[local/php_interface/lib/Helpers/VideoQualityHelper.php](local/php_interface/lib/Helpers/VideoQualityHelper.php)` в namespace `Maxima\Helpers` (единственное место для общей логики качеств):
  - Вход: **одна** строка web-пути к основному файлу (как в шаблоне сейчас: `/upload/.../file.mp4`).
  - Нормализация пути: ведущий `/`, склейка с `$_SERVER['DOCUMENT_ROOT']` (как в `[competitions/template.php](local/templates/MaximaTV/components/bitrix/catalog.section/competitions/template.php)`).
  - Вычислить `$dir = dirname(webPath)`, `$basename` через `basename(webPath)`.
  - Каталог альтернатив: `$dir . '/ext/'`. Если каталога нет или `scandir` недоступен — вернуть массив из **одного** элемента (только исходный URL, метка «оригинал» / 1080p).
  - **Поиск по имени:** для каждого имени файла в `ext/` (кроме `.`, `..`) проверить соответствие шаблону: имя файла = `{prefix}_{basename}`, где `prefix` — непустая строка до первого разделителя (рекомендуется regex вида `^(.+)_(\Q$basename\E)$` с `preg_quote` для basename, чтобы корректно работать, если в имени есть символы для regex). Так отфильтровываются посторонние файлы в `ext/`.
  - Для каждого подошедшего файла: web-URL = `$dir . '/ext/' . $fileName`, поле метки для UI = `prefix` (или нормализованное, например `480p`, если префикс числовой — по желанию оставить как в ФС).
  - Сортировка вариантов из `ext/`: сначала по «весу» качества — практично сортировать числовые префиксы по возрастанию; нечисловые — в конце в лексикографическом порядке (или документировать одно правило в комментарии хелпера). Исходный файл всегда отдельной записью с наивысшим приоритетом (последний в списке качеств в меню или первый как default — зафиксировать в реализации единообразно).
  - Выход: массив источников для плеера, например `[['label' => '...', 'src' => '...', 'isSource' => false], ..., ['label' => '1080p', 'src' => original, 'isSource' => true]]`.
  - Если в `ext/` не найдено ни одного `{prefix}_{basename}` — массив из **одного** элемента (только исходный файл) — UI качества не показываем.
2. В каждом затронутом `template.php` после вычисления финального `$videoUrl` (из `VIDEO_FILE` или из `$videoFile` после scandir) вызвать хелпер и передать результат в разметку (см. ниже).

**Затронутые шаблоны (синхронно одна и та же логика):**

- `[news/events/bitrix/news.detail/.default/template.php](local/templates/MaximaTV/components/bitrix/news/events/bitrix/news.detail/.default/template.php)`
- `[news/events.v2/bitrix/news.detail/.default/template.php](local/templates/MaximaTV/components/bitrix/news/events.v2/bitrix/news.detail/.default/template.php)`
- `[news/events/bitrix/news.detail/master-class/template.php](local/templates/MaximaTV/components/bitrix/news/events/bitrix/news.detail/master-class/template.php)`
- `[news/events.v2/bitrix/news.detail/master-class/template.php](local/templates/MaximaTV/components/bitrix/news/events.v2/bitrix/news.detail/master-class/template.php)`
- `[catalog.section/competitions/template.php](local/templates/MaximaTV/components/bitrix/catalog.section/competitions/template.php)` (блок с Video.js, не трогая особый кейс `ID == 9781` с нативным `<video>`)

## Клиентская часть (Video.js)

Video.js **не** показывает меню качества для нескольких MP4 из коробки; для MP4 нужен либо плагин, либо тонкая обёртка.

**Рекомендуемый путь:** добавить проверенный плагин для переключения **progressive** источников (например **@silvermine/videojs-quality-selector** или **videojs-resolution-switcher**), положить минифицированные `*.js` + `*.css` рядом с существующим `video.js`, подключать **только если** в PHP сформировано `count($sources) > 1`.

Инициализация:

- Вызов `videojs(id, options)` с массивом `sources` (каждый `{ src, type: 'video/mp4', label }`) или последующий `player.updateSrc(...)` в зависимости от API выбранного плагина.
- Сохранить текущий код **hotkeys** и блокировку **contextmenu** на `.event__video video`.
- При смене качества: сохранять `currentTime` и `paused`, после `loadedmetadata` восстанавливать позицию (стандартный паттерн для смены `src`).

Если по лицензии/размеру не хотите третий пакет — запасной вариант: свой `MenuButton` / выпадающий список над плеером с `player.src({...})` и тем же сохранением времени; это чуть больше кода, но без новых файлов.

## Разметка

- Один `<video id="MaximaTV-video" ...>` без дублирующих `<source>` в HTML **или** несколько `<source>` с `data`-атрибутами — по выбранному плагину; главное — не ломать `fluid` и poster.
- Передача данных: безопасный `json_encode` массива источников в `data-maxima-video-sources` на элементе `<video>` (или один inline-скрипт с `var MaximaTVVideoSources = ...` рядом с плеером), чтобы не дублировать логику в пяти местах — опционально вынести общий фрагмент в include, например `[local/templates/MaximaTV/include/maxima_video_player.php](local/templates/MaximaTV/include/maxima_video_player.php)`, принимающий `$arVideoSources` и `$previewSrc`.

## Сохранение плана в репозитории

После согласования: сохранить этот план как файл в проекте, например `[.cursor/plans/video-quality-selection.md](.cursor/plans/video-quality-selection.md)` (создать каталог при отсутствии). Это соответствует типичной раскладке Cursor (артефакты рядом с `.cursor/rules/`), не конфликтует с `[AGENTS.md](AGENTS.md)` (отдельный запрос на документ плана).

## Риски и проверки

- **Кэш компонентов:** при появлении файлов в `ext/` кэш шаблона может отдавать старую разметку — при необходимости убедиться, что теги кэша/время кэша учитывают файловую систему (как в остальных местах проекта).
- **Кодировка путей:** имена с нестандартными символами — `basename`/`dirname` на PHP достаточно.
- **Ручная проверка:** страница с одним источником — без меню качества; при нескольких `{prefix}_{basename}` в `ext/` — переключение и сохранение позиции.
- **Имена с подчёркиваниями в `basename`:** шаблон `{prefix}_{полный_basename}` — разбор только если префикс отделён от **полного** `basename` первым подходящим `_` (regex выше привязывает конец строки к полному basename).

