# Maxima TV

Сайт **Maxima TV** на платформе **1C-Битрикс: Управление сайтом** (ядро **Bitrix Framework ≥ 25.750.0**). Публичная часть использует инфоблоки, стандартные и кастомные компоненты.

## Что в репозитории

| Путь | Назначение |
|------|------------|
| `index.php` | Точка входа главной и подключение `bitrix/header.php` |
| `local/` | Кастомная логика: шаблоны, компоненты, модули, `php_interface` |
| `bitrix/` | Ядро и модули продукта — **не версионируется** (см. `.gitignore`); ставится на окружение отдельно |

Кастомизация сосредоточена в `local/`:

- **Шаблон сайта:** `local/templates/MaximaTV/`
- **Компоненты:** `local/components/mxm/` (namespace `mxm`)
- **Модули:** `local/modules/maxima.options/`, `local/modules/maxima.search/`

## Требования к окружению

- PHP с расширениями, рекомендуемыми для выбранной редакции 1C-Битрикс (см. [требования к ПО](https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=32)).
- Установленное ядро в каталоге `bitrix/` относительно корня сайта (копия с боевого сервера, «Битрикс24 в коробке» / дистрибутив или общий путь деплоя — по договорённости команды).
- Веб-сервер (Apache/Nginx + PHP-FPM) и настройки `urlrewrite` для ЧПУ, если используются.

Секреты и конфигурация БД — только на сервере (`bitrix/.settings.php`, `bitrix/php_interface/dbconn.php` и т.д.), в Git не попадают.

## Документация и соглашения

- Официальная документация для разработчиков: [dev.1c-bitrix.ru](https://dev.1c-bitrix.ru/api_help/).
- Подсказки для ИИ-ассистентов в Cursor: [`AGENTS.md`](AGENTS.md).
- Игнорируемые пути Git: [`.gitignore`](.gitignore).

## Git

Сообщения коммитов — на **английском**, формат [**Conventional Commits**](https://www.conventionalcommits.org/en/v1.0.0/) (`feat:`, `fix:`, `chore:` и т.д.).
