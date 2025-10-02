## Instalación del proyecto

Para poder realizar la instalaciónn correctamente se necesita PHP 8.xx, composer y Node.js en sus ultimas veriones para mayor estabilidad.

1. Descargar el proyecto (o clonarlo usando GIT)
2. Copie el archivo `.env.example` en su archivo `.env` y configure las credenciales de la base de datos
3. Navegue al directorio raíz del proyecto usando la terminal
4. Ejecute el comonado `composer install`
5. Establezca la clave de cifrado ejecutando el comando `php artisan key:generate --ansi`
6. Ejecute las migraciones con el comando `php artisan migrate`
7. Inicie el servidor local de laravel con el comando `php artisan serve`
8. Revise la documentación en el endpoint api/documentation
9. Puede realizar pruebas por medio de cualquier gestor HTTP como postman, bruno, Rest Client etc...
