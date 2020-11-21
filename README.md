# WooCommerce Azul Payment Gateway

Plugin de WordPress para realizar la configuración de Azul por el Banco Popular Dominicano en cualquier sitio web.

Pasos para hacerlo funcionar:
1. Instala el plugin AzulPaymentGateway
2. Copia los archivos que se encuentran en la carpeta "theme" y colocalos en /var/www/html/wp-content/themes/"directorio de tu tema principal"
3. Crea una pagina para "Process Order" en wordpress y asignale la plantilla "Azul - Order Process".
4. Crea una pagina para "Completed" en wordpress y asignale la plantilla "Azul - Order Complete".
5. Ve a Plugins -> WooCommerce -> Ajustes -> Payments -> Azul Payments Gateway -> administrar.
6. Coloca todos tus datos de configuración.

Nota: En "Process Page Azul" coloca el URL de la página que creaste en el paso 3.

Nota: En "Pagina de aprobacion" coloca el URL de la página que creaste en el paso 4.

Y listo ya con esto estaría funcionando, es un plugin bastante sencillo pero que hace el trabajo y te permitirá realizar pruebas para que Azul te apruebe tu pasarela.