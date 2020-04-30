<?php

return [
    'plugin' => [
        'name' => 'Менеджер хлебных крошек',
        'description' => 'Настройка и упраление хлебными крошками на страницах сайта.',
        'tab' => 'Хлебные крошки',
        'access' => [
            'breadcrumbs' => 'Управление хлебными крошками',
            'templates' => 'Управление шаблонами хлебных крошек',
            'settings' => 'Управление настройками менеджера хлебных крошек'
        ],
        'settings' => [
            'breadcrumbs' => [
                'label' => 'Хлебные крошки',
                'description' => 'Управление хлебными крошками.'
            ],
            'templates' => [
                'label' => 'Шаблоны',
                'description' => 'Управление шаблонами отображения хлебных крошек.'
            ],
            'settings' => [
                'label' => 'Менеджер хлебных крошек',
                'description' => 'Настройки менеджера хлебных крошек.'
            ],
            'category' => 'Хлебные крошки'
        ]
    ],
    'config' => [
        'data_types' => [
            'static'        => 'Статические',
            'static_plus'   => 'Статические +',
            'cms'           => 'CMS'
        ],
        'title_fields' => [
            'title'         => 'Заголовок (title)',
            'meta_title'    => 'Заголовок мета (meta title)',
            'crumb_title'   => 'Заголовок хлебной крошки (crumb title)'
        ]
    ],
    'components' => [
        'breadcrumbs' => [
            'name' => 'Хлебные крошки',
            'description' => 'Подключение хлебных крошек на страницу.',
            'properties' => [
                'template_title' => 'Шаблоны',
                'template_description' => 'Выберите шаблон который будет использоваться для рендеринга хлебных крошек.',
                'isjsonld_title' => 'Использовать JSON-LD',
                'isjsonld_description' => 'Подключить разметку JSON-LD для хлебных крошек.'
            ]
        ]
    ],
    'controllers' => [
        'breadcrumbs' => [
            'list_toolbar' => [
                'new_breadcrumb' => 'Новая хлебная крошка',
                'import_pages' => 'Импортировать страницы',
                'import_pages_indicator' => 'Импортирование страниц...',
                'import_pages_confirm' => 'Вы уверены, что хотите начать импорт страниц из CMS?'
            ],
            'preview_scoreboard' => [
                'record_id' => 'ID записи',
                'title' => 'Заголовок',
                'parent' => 'Родитель',
                'parent_not_found' => 'отсутствует',
                'data_type' => 'Тип данных',
                'updated' => 'Редактирование',
                'created' => 'Создание'
            ],
            'preview_toolbar' => [
                'back_to_list' => 'Вернуться к списку',
                'update_data' => 'Изменить данные'
            ],
            'reorder_records' => [
                'preview' => 'просмотреть'
            ],
            'config_filter' => [
                'parents' => 'Родители',
                'data_type' => 'Тип данных',
                'show_deleted' => 'Показать удаленные'
            ],
            'config_form' => [
                'name' => 'Breadcrumb',
                'create_title' => 'Create Breadcrumb',
                'update_title' => 'Edit Breadcrumb',
                'preview_title' => 'Preview Breadcrumb'
            ],
            'config_list' => [
                'title' => 'Manage Breadcrumbs'
            ],
            'breadcrumb' => 'Breadcrumbs'
        ],
        'templates' => [
            'list_toolbar' => [
                'new_template' => 'New Template'
            ],
            'config_filter' => [
                'show_actived' => 'Показать активные',
                'show_deleted' => 'Показать удаленные'
            ],
            'config_form' => [
                'name' => 'Template',
                'create_title' => 'Create Template',
                'update_title' => 'Edit Template',
                'preview_title' => 'Preview Template'
            ],
            'config_list' => [
                'title' => 'Manage Templates'
            ],
            'breadcrumb' => 'Templates'
        ],
        'create' => 'Create',
        'create_indicator' => 'Creating record...',
        'create_and_close' => 'Create and Close',
        'create_and_close_indicator' => 'Creating record...',
        'save' => 'Save',
        'save_indicator' => 'Saving record...',
        'save_and_close' => 'Save and Close',
        'save_and_close_indicator' => 'Saving record...',
        'delete' => 'Удалить',
        'delete_indicator' => 'Удаление записи...',
        'delete_confirm' => 'Вы уверены, что хотите удалить текущую запись?',
        'restore' => 'Восстановить',
        'restore_indicator' => 'Восстановление записи...',
        'restore_confirm' => 'Вы уверены, что хотите восстановить текущую запись?',
        'or' => 'or',
        'cancel' => 'Cancel',
        'return_to_list' => 'Return to list',
        'list_toolbar' => [
            'bulk_actions' => 'Массовые действия',
            'soft_delete' => 'Удалить',
            'soft_delete_confirm' => 'Пометить на удаление выбранные записи?',
            'restore' => 'Восстановить',
            'restore_confirm' => 'Восстановить выбранные записи?',
            'delete' => 'Полное удаление',
            'delete_confirm' => 'Удалить выбранные записи и все что с ними связано?'
        ],
        'hint_trashed' => [
            'title' => 'Эта запись была удалена!',
            'message' => 'Для восстановления данной записи нажмите кнопку "Восстановить".'
        ],
        'messages' => [
            'delete_success' => 'Запись была успешно удалена!',
            'restore_success' => 'Запись была успешно восстановлена!',
            'import_pages_success' => 'Импортирование страниц успешно завершено!',
            'bulk_action_success' => 'Изменения в выбранные данные успешно внесены!',
            'bulk_action_error' => 'Ошибка при выполнении выбранного действия!'
        ]
    ],
    'models' => [
        'breadcrumb' => [
            'id' => 'ID',
            'name' => 'Название хлебных крошек',
            'parent' => 'Родитель',
            'parent_placeholder' => '-- выберите родителя --',
            'data_type' => 'Тип данных',
            'data_type_placeholder' => '-- выберите метод получения данных --',
            'code' => 'Код подключения хлебных крошек',
            'static_title' => 'Заголовок',
            'static_title_placeholder' => 'Главная',
            'static_link' => 'Относительная ссылка',
            'static_plus_title' => 'Заголовок',
            'static_plus_title_placeholder' => 'Пользователи',
            'static_plus_segment' => 'Сегмент ссылки',
            'cms_page' => 'Страница',
            'cms_page_placeholder' => '-- выберите страницу --',
            'cms_title_field' => 'Поле заголовка',
            'cms_title_field_placeholder' => '-- выберите поле --',
            'hint_information' => [
                1 => 'Внимание!',
                2 => 'Данный код подключения на прямую зависит от родительских элементов.',
                3 => 'По этому при изменении родительских элементов он может значительно измениться!'
            ]
        ],
        'template' => [
            'id' => 'ID',
            'title' => 'Заголовок',
            'title_comment' => 'Короткое описание шаблона',
            'code' => 'Код шаблона',
            'is_active' => 'Активный шаблон',
            'is_active_on' => 'Да',
            'is_active_off' => 'Нет'
        ],
        'settings' => [
            'hint_information' => 'Для избежания проблем включайте кеширование только после настройки всех данных!',
            'use_breadcrumbs_cache' => 'Кеширование хлебных крошек',
            'use_templates_cache' => 'Кеширование шаблонов',
            'breadcrumbs_cache_expire' => 'Время кеширования хлебных крошек',
            'breadcrumbs_cache_expire_comment' => 'Указывается в минутах',
            'templates_cache_expire' => 'Время кеширования шаблонов',
            'templates_cache_expire_comment' => 'Указывается в минутах'
        ],
        'crumb_title' => [
            'label' => 'Заголовок хлебной крошки',
            'tab' => 'Хлебные крошки',
            'comment' => 'Опциональный статический заголовок хлебной крошки для этой страницы.'
        ],
        'deleted_at' => 'Удаление',
        'created_at' => 'Создание',
        'updated_at' => 'Обновление'
    ],
    'exceptions' => [
        'breadcrumbs_zero' => 'Количество хлебных крошек не может быть нулевым!',
        'parameters_not_found' => 'Переменная "breadcrumbs_parameters" не задана, или задана не корректно!',
        'templates_not_found' => 'Шаблон с идентификатором %s не найден!',
        'breadcrumbs_not_found' => 'Хлебные крошки с идентификатором %s не найдены!'
    ]
];