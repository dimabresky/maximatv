---
name: Video quality selection
overview: Добавить серверную сборку списка URL для качеств 480/720 рядом с исходным 1080 (папка `ext/` с префиксами), клиентский выбор качества в Video.js для всех шаблонов с основным плеером, с полным откатом к текущему поведению при одном файле. Вся общая серверная логика — в одном хелпере в `local/php_interface/lib/Helpers`. План-документ сохранить в репозитории под `.cursor/plans/` (стандартная зона Cursor для артефактов агента).
todos:
  - id: helper
    content: Добавить VideoQualityHelper в local/php_interface/lib/Helpers — вся общая логика (нормализация пути, ext/480_, ext/720_, file_exists, массив источников); шаблоны только вызывают хелпер.
    status: pending
  - id: templates
    content: Подключить хелпер в 5 шаблонов Video.js; при count>1 выводить данные для плеера; оставить один источник без изменений UX.
    status: pending
  - id: frontend
    content: Подключить плагин переключения MP4 + инициализация videojs/hotkeys/contextmenu; смена src с восстановлением currentTime.
    status: pending
  - id: plan-file
    content: Сохранить утверждённый план в .cursor/plans/video-quality-selection.md.
    status: pending
isProject: false
---

# План: выбор качества видео (Video.js)

## Контекст

- Плеер: Video.js **7.6.6** из [`local/templates/MaximaTV/js/videojs/video.js`](local/templates/MaximaTV/js/videojs/video.js), стили [`local/templates/MaximaTV/css/videojs/video-js.css`](local/templates/MaximaTV/css/videojs/video-js.css), hotkeys [`videojs.hotkeys.min.js`](local/templates/MaximaTV/js/videojs/videojs.hotkeys.min.js).
- Сейчас везде один `<source src="...">` и `videojs('MaximaTV-video')` + hotkeys (пример: [`local/templates/MaximaTV/components/bitrix/news/events/bitrix/news.detail/.default/template.php`](local/templates/MaximaTV/components/bitrix/news/events/bitrix/news.detail/.default/template.php) строки 91–118).
- Два способа получения URL основного файла:
  - **`VIDEO_FILE`** — готовый URL в свойстве (`.default` для `events` / `events.v2`).
  - **`PATH_TO_VIDEO` + scandir** — относительный путь к каталогу, выбор файла по индексу `VNUM` ([`master-class/template.php`](local/templates/MaximaTV/components/bitrix/news/events/bitrix/news.detail/master-class/template.php), [`catalog.section/competitions/template.php`](local/templates/MaximaTV/components/bitrix/catalog.section/competitions/template.php)).

Правило файлов (как вы описали): для пути вида `.../dir/video.mp4` проверять наличие `.../dir/ext/480_video.mp4` и `.../dir/ext/720_video.mp4`; исходный файл считать **1080p**.

```mermaid
flowchart LR
  webPath[Web path to 1080 file]
  helper[VideoQualityHelper PHP]
  fs[file_exists on DOCUMENT_ROOT]
  json[Sources JSON for player]
  webPath --> helper
  helper --> fs
  fs --> json
```

## Серверная часть

1. Добавить хелпер [`local/php_interface/lib/Helpers/VideoQualityHelper.php`](local/php_interface/lib/Helpers/VideoQualityHelper.php) в namespace `Maxima\Helpers` (единственное место для общей логики качеств):
   - Вход: **одна** строка web-пути к основному файлу (как в шаблоне сейчас: `/upload/.../file.mp4`).
   - Нормализация: убедиться, что путь приводится к виду с ведущим `/` для склейки с `$_SERVER['DOCUMENT_ROOT']` (как в [`competitions/template.php`](local/templates/MaximaTV/components/bitrix/catalog.section/competitions/template.php) при работе с путями).
   - Для `basename` и `dirname` строить кандидатов: `{dirname}/ext/480_{basename}`, `{dirname}/ext/720_{basename}`.
   - Проверка: `is_file(DOCUMENT_ROOT . $candidatePath)` (только если файл реально есть).
   - Выход: упорядоченный массив вариантов, например `[['height' => 1080, 'src' => '...'], ...]` — всегда включать исходный URL как 1080; добавлять 720/480 только при наличии файла.
   - Если ни одного «дополнительного» нет — массив из **одного** элемента (1080) — дальше UI не показываем, поведение как сейчас.

2. В каждом затронутом `template.php` после вычисления финального `$videoUrl` (из `VIDEO_FILE` или из `$videoFile` после scandir) вызвать хелпер и передать результат в разметку (см. ниже).

**Затронутые шаблоны (синхронно одна и та же логика):**

- [`news/events/bitrix/news.detail/.default/template.php`](local/templates/MaximaTV/components/bitrix/news/events/bitrix/news.detail/.default/template.php)
- [`news/events.v2/bitrix/news.detail/.default/template.php`](local/templates/MaximaTV/components/bitrix/news/events.v2/bitrix/news.detail/.default/template.php)
- [`news/events/bitrix/news.detail/master-class/template.php`](local/templates/MaximaTV/components/bitrix/news/events/bitrix/news.detail/master-class/template.php)
- [`news/events.v2/bitrix/news.detail/master-class/template.php`](local/templates/MaximaTV/components/bitrix/news/events.v2/bitrix/news.detail/master-class/template.php)
- [`catalog.section/competitions/template.php`](local/templates/MaximaTV/components/bitrix/catalog.section/competitions/template.php) (блок с Video.js, не трогая особый кейс `ID == 9781` с нативным `<video>`)

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
- Передача данных: безопасный `json_encode` массива источников в `data-maxima-video-sources` на элементе `<video>` (или один inline-скрипт с `var MaximaTVVideoSources = ...` рядом с плеером), чтобы не дублировать логику в пяти местах — опционально вынести общий фрагмент в include, например [`local/templates/MaximaTV/include/maxima_video_player.php`](local/templates/MaximaTV/include/maxima_video_player.php), принимающий `$arVideoSources` и `$previewSrc`.

## Сохранение плана в репозитории

После согласования: сохранить этот план как файл в проекте, например [`.cursor/plans/video-quality-selection.md`](.cursor/plans/video-quality-selection.md) (создать каталог при отсутствии). Это соответствует типичной раскладке Cursor (артефакты рядом с `.cursor/rules/`), не конфликтует с [`AGENTS.md`](AGENTS.md) (отдельный запрос на документ плана).

## Риски и проверки

- **Кэш компонентов:** при появлении файлов в `ext/` кэш шаблона может отдавать старую разметку — при необходимости убедиться, что теги кэша/время кэша учитывают файловую систему (как в остальных местах проекта).
- **Кодировка путей:** имена с нестандартными символами — `basename`/`dirname` на PHP достаточно.
- **Ручная проверка:** страница события/соревнования с одним файлом — без меню качества; с тремя файлами в `ext/` — переключение и сохранение позиции.
