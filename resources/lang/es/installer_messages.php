<?php 
return array (
  'title' => 'Instalador de Laravel',
  'next' => 'Siguiente',
  'back' => 'Anterior',
  'finish' => 'Instalar',
  'forms' => 
  array (
    'errorTitle' => 'Los siguientes errores ocurrieron:',
  ),
  'welcome' => 
  array (
    'templateTitle' => 'Bienvenido',
    'title' => 'Bienvenido al instalador',
    'message' => 'Bienvenido al asistente de configuración',
    'next' => 'Verifique los requisitos',
  ),
  'requirements' => 
  array (
    'templateTitle' => 'Paso 1 | Requisitos del servidor',
    'title' => 'Requisitos',
    'next' => 'Comprobar permisos',
  ),
  'permissions' => 
  array (
    'templateTitle' => 'Paso 2 | Permisos',
    'title' => 'Permisos',
    'next' => 'Configurar entorno',
  ),
  'environment' => 
  array (
    'menu' => 
    array (
      'templateTitle' => 'Paso 3 | Ajustes de entorno',
      'title' => 'Ajustes de entorno',
      'desc' => 'Seleccione cómo desea configurar el archivo de aplicaciones <code> .env </code>.',
      'wizard-button' => 'Configuración del asistente de formulario',
      'classic-button' => 'Editor de texto clásico',
    ),
    'wizard' => 
    array (
      'templateTitle' => 'Paso 3 | Ajustes de entorno | Mago guiado',
      'title' => 'Asistente <code> .env </code> guiado',
      'tabs' => 
      array (
        'environment' => 'Ambiente',
        'database' => 'Base de datos',
        'application' => 'Solicitud',
      ),
      'form' => 
      array (
        'name_required' => 'Se requiere un nombre de entorno.',
        'app_name_label' => 'Nombre de la aplicación',
        'app_name_placeholder' => 'Nombre de la aplicación',
        'app_environment_label' => 'Entorno de aplicación',
        'app_environment_label_local' => 'Local',
        'app_environment_label_developement' => 'Desarrollo',
        'app_environment_label_qa' => 'Qa',
        'app_environment_label_production' => 'Producción',
        'app_environment_label_other' => 'Otro',
        'app_environment_placeholder_other' => 'Entra en tu entorno ...',
        'app_debug_label' => 'Depuración de la aplicación',
        'app_debug_label_true' => 'Cierto',
        'app_debug_label_false' => 'Falso',
        'app_log_level_label' => 'Nivel de registro de la aplicación',
        'app_log_level_label_debug' => 'depurar',
        'app_log_level_label_info' => 'info',
        'app_log_level_label_notice' => 'darse cuenta',
        'app_log_level_label_warning' => 'advertencia',
        'app_log_level_label_error' => 'error',
        'app_log_level_label_critical' => 'crítico',
        'app_log_level_label_alert' => 'alerta',
        'app_log_level_label_emergency' => 'emergencia',
        'app_url_label' => 'Url de la aplicación',
        'app_url_placeholder' => 'Url de la aplicación',
        'db_connection_label' => 'Conexión de base de datos',
        'db_connection_label_mysql' => 'mysql',
        'db_connection_label_sqlite' => 'sqlite',
        'db_connection_label_pgsql' => 'pgsql',
        'db_connection_label_sqlsrv' => 'sqlsrv',
        'db_host_label' => 'Base de datos host',
        'db_host_placeholder' => 'Base de datos host',
        'db_port_label' => 'Puerto de base de datos',
        'db_port_placeholder' => 'Puerto de base de datos',
        'db_name_label' => 'Nombre de la base de datos',
        'db_name_placeholder' => 'Nombre de la base de datos',
        'db_username_label' => 'Nombre de usuario de la base de datos',
        'db_username_placeholder' => 'Nombre de usuario de la base de datos',
        'db_password_label' => 'Contraseña de la base de datos',
        'db_password_placeholder' => 'Contraseña de la base de datos',
        'app_tabs' => 
        array (
          'more_info' => 'Más información',
          'broadcasting_title' => 'Radiodifusión, almacenamiento en caché, sesión, & amp; Cola',
          'broadcasting_label' => 'Controlador de transmisión',
          'broadcasting_placeholder' => 'Controlador de transmisión',
          'cache_label' => 'Controlador de caché',
          'cache_placeholder' => 'Controlador de caché',
          'session_label' => 'Controlador de sesión',
          'session_placeholder' => 'Controlador de sesión',
          'queue_label' => 'Conductor de cola',
          'queue_placeholder' => 'Conductor de cola',
          'redis_label' => 'Redis Driver',
          'redis_host' => 'Redis Host',
          'redis_password' => 'Contraseña redis',
          'redis_port' => 'Puerto redis',
          'mail_label' => 'Correo',
          'mail_driver_label' => 'Controlador de correo',
          'mail_driver_placeholder' => 'Controlador de correo',
          'mail_host_label' => 'Host de correo',
          'mail_host_placeholder' => 'Host de correo',
          'mail_port_label' => 'Puerto de correo',
          'mail_port_placeholder' => 'Puerto de correo',
          'mail_username_label' => 'Nombre de usuario del correo',
          'mail_username_placeholder' => 'Nombre de usuario del correo',
          'mail_password_label' => 'Contraseña de correo',
          'mail_password_placeholder' => 'Contraseña de correo',
          'mail_encryption_label' => 'Cifrado de correo',
          'mail_encryption_placeholder' => 'Cifrado de correo',
          'pusher_label' => 'Arribista',
          'pusher_app_id_label' => 'ID de la aplicación del empujador',
          'pusher_app_id_palceholder' => 'ID de la aplicación del empujador',
          'pusher_app_key_label' => 'Pusher App Key',
          'pusher_app_key_palceholder' => 'Pusher App Key',
          'pusher_app_secret_label' => 'Pusher App Secret',
          'pusher_app_secret_palceholder' => 'Pusher App Secret',
        ),
        'buttons' => 
        array (
          'setup_database' => 'Configuración de la base de datos',
          'setup_application' => 'Aplicación de configuración',
          'install' => 'Instalar',
        ),
      ),
    ),
    'classic' => 
    array (
      'templateTitle' => 'Paso 3 | Ajustes de entorno | Editor clásico',
      'title' => 'Editor de Ambiente Clásico',
      'save' => 'Guardar .env',
      'back' => 'Usar asistente de formulario',
      'install' => 'Instalar',
    ),
    'success' => 'Los cambios en tu archivo .env han sido guardados.',
    'errors' => 'No es posible crear el archivo .env, por favor intentalo manualmente.',
  ),
  'install' => 'Instalar',
  'installed' => 
  array (
    'success_log_message' => 'Instalador Laravel exitosamente INSTALADO en',
  ),
  'final' => 
  array (
    'title' => 'Finalizado.',
    'templateTitle' => 'Instalación terminada',
    'finished' => 'La aplicación ha sido instalada con éxito!',
    'migration' => 'Migración y salida de la consola de semillas:',
    'console' => 'Salida de consola de aplicación:',
    'log' => 'Entrada de registro de instalación:',
    'env' => 'Archivo .env final:',
    'exit' => 'Haz click aquí para salir.',
  ),
  'updater' => 
  array (
    'title' => 'Laravel Updater',
    'welcome' => 
    array (
      'title' => 'Bienvenido al actualizador',
      'message' => 'Bienvenido al asistente de actualización.',
    ),
    'overview' => 
    array (
      'title' => 'Visión general',
      'message' => 'Hay 1 actualización. | Hay actualizaciones de número.',
      'install_updates' => 'Instalar actualizaciones',
    ),
    'final' => 
    array (
      'title' => 'Terminado',
      'finished' => 'La base de datos de la aplicación se ha actualizado con éxito.',
      'exit' => 'Haga clic aquí para salir',
    ),
    'log' => 
    array (
      'success_message' => 'Laravel Installer actualizado con éxito en',
    ),
  ),
);