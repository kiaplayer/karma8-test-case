# Тестовое задание

* [Текст задачи](task.pdf)
* [Решение](answer)

## Скрипт db.php

В него вынесены все параметры подключения к БД, в данном решении используется PostgreSQL.

Передача параметров подключения в данном решении выполняется через ENV-переменные.
Альтернативные варианты передачи конфигурационных параметров:
- параметры командной строки;
- общий конфигурационный файл (PHP, JSON, YAML);
- внешние системы (HashiCorp Vault, Consul и т.д).

## Скрипт common.php

В него вынесена функция для логирования возникающих ошибок.

В данном решении реализовано простейшее логирование с остановкой работы при любой ошибке. 
В production-окружения такое решение не подойдет, его необходимо доработать:
- Рассмотреть возможность не завершать скрипт при любой ошибке, какие-то ошибки могут быть обходимыми.
- Вывод ошибок в json-формате для удобства их дальнейшей обработки (например, сбор ошибок в k8s-окружении с отправкой в ELK). 
- Можно дублировать ошибки в систему агрегации (например, Sentry), чтобы видеть статистику их появления.
- Для критичных ошибок должна быть настроена система уведомления дежурного инженера.

## Скрипт check_emails.php

Запускается по расписанию в cli-режиме (через cron) и валидирует email-адреса.

Для сокращения времени работы скрипта (а также финансовых расходов) делаем допущение,
что подтвержденные пользователями адреса уже являются валидными email-адресами, 
так как пользователь уже благополучно получил письмо для подтверждения.

Таким образом, проверить нужно только еще не подтвержденные адреса (с помощью функции check_email).

## Скрипт send_emails.php

Запускается по расписанию в cli-режиме (через cron) и рассылает уведомления о скором завершении подписки.

Всем пользователям, у которых через 1, 2 или 3 дня истечет подписка, отправляется email по заданному шаблону (с помощью функции send_email).

Во избежание лишних уведомлений необходимо запускать скрипт не чаще одного раза в сутки.

В данном решении специфичные настройки скрипта вынесены в виде переменных в начале файла.
Для production-окружения это не лучшее решение. 
Лучше применить один из способов, описанных в разделе "Скрипт db.php".