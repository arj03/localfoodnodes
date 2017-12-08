<?php

return [
    // Request errors
    'request_no_producer' => 'No encontramos el productor solicitado',
    'request_no_product' => 'No encontramos el producto solicitado',
    'request_no_variant' => 'No encontramos la variante del producto solicitado',
    'request_no_order' => 'No encontramos el pedido solicitado',
    'request_no_order_item' => 'No encontramos la línea del pedido solicitada',
    'request_no_event' => 'No encontramos el evento solicitado',
    'request_no_node' => 'No encontramos el nodo solicitado',

    // Event
    'event_created' => 'Tu evento ha sido creado',
    'event_updated' => 'Tu evento ha sido editado',
    'event_deleted' => 'Tu evento ha sido eliminado',

    // Node
    'node_created' => 'Tu nodo ha sido creado',
    'node_updated' => 'Tu nodo ha sido editado',
    'node_deleted' => 'Tu nodo ha sido eliminado',

    // Producer
    'producer_created' => 'Tu cuenta productor ha sido creada',
    'producer_updated' => 'Tu cuenta productor ha sido editada',
    'producer_deleted' => 'Tu cuenta productor ha sido eliminado',

    // Product
    'product_created' => 'Tu producto ha sido creado',
    'product_updated' => 'Tu producto ha sido editado',
    'product_deleted' => 'Tu producto ha sido eliminado',
    'product_error_create_variant' => 'No pudimos crear la variante del producto',
    'product_no_production' => 'No has creado algún producto',
    'product_date_quantity_required' => 'Es obligatorio llenar los campos de fecha y número para productos ocasional en venta',
    'product_delivery_updated' => 'Tus fechas de entrega han sido editadas',

    // Variant
    'variant_created' => 'Tu variante ha sido creada',
    'variant_updated' => 'Tu variante ha sido editada',
    'variant_deleted' => 'Tu variante ha sido eliminado',

    // Production
    'production_created' => 'La producción ha sido creada',
    'production_updated' => 'La producción ha sido editada',
    'production_deleted' => 'La producción ha sido eliminado',

    // Admin management
    'admin_removed' => 'Ya no estás administrador de :nombre',
    'invite_no_user' => 'El usario no existe',
    'invite_cancelled' => 'La invitación ha sido cancelado',
    'invite_accepted' => 'Ahora estás administrador de :nombre',
    'invite_sent' => 'La invitación ha sido enviado.',
    'user_invited' => 'El usario ya está invitado',
    'user_is_admin' => 'El usario ya está administrator',

    // User
    'user_account_email_sent' => 'Un correo electrónico de activación ha sido enviado a tu dirección de correo electrónico.',
    'user_account_activated' => 'Tu cuenta ha sido activado.',
    'user_account_activation_failed' => 'Tu cuento no se pudo activar. Para obtener ayuda contacta el soporte.',
    'user_account_created' => 'Tu cuenta ha sido creado.',
    'user_account_updated' => 'Tu información ha sido editada',
    'user_account_deleted' => 'Tu cuenta ha sido eliminada',
    'user_password_changed' => 'Tu contraseña ha sido cambiada',
    'user_password_changed' => 'Tu contraseña ha sido cambiada',
    'user_membership_amount_not_numeric' => 'La sifra tiene que ser un número.',
    'user_membership_success' => 'Ahora tu membresía ha sido pagada.',
    'user_membership_error' => 'No se pudo realizar el pago de tu membresía. :errors',

    // Password reset
    'password_reset_email_sent' => 'Un correo electrónico con un enlace para restablecer tu contraseña ha sido enviado a tu dirección de correo electrónico.',

    // Other
    'required_fields_missing' => 'Algunos campos obligatorios están vacíos.',
    'required' => 'Obligatorio',
    'session_expired' => 'Tu sesión ha expirado, por favor ingresa nuevamente.',
    'order_deleted' => 'Tu pedido ha sido eliminado.',

    // Auth
    'invalid_login_request' => 'Solicitud de accesso inválido.',
    'invalid_access_token' => 'Acceso inválido.',
    'invalid_user_data' => 'Usario inválido.',
    'reset_link_expired' => 'Enlace de restablecimiento inválido.',
    'invalid_login' => 'Credenciales de acceso incorrectas.',

    // Migration
    'user_migrate_not_valid' => 'Tu dirección de correo electrónico no se pudo migrar, por favor crea una cuenta nueva.',
    'user_migrate_already_exists' => 'Tu cuenta ya ha sido migrada.',
    'user_migrate_done' => 'La migración de la cuenta está completa.',
];
