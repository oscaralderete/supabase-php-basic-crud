# Supabase PHP basic SPA CRUD
## Sencillo ejercicio de fundamentos de PHP que usa Supabase como data storage

Hola, este proyecto es un sencillo ejemplo de la interacción de PHP con Supabase usando un 'hand made router'.

¿Por qué usar un router en un proyecto de PHP si PHP soporta 'out of the box' la navegación por file/directory? La respuesta es porque un router le añade seguridad, eficiencia, escalabilidad. En este caso escribí un router desde cero -como un ejercicio de programación- pero lo ideal sería usar uno ya hecho usando Composer.

Si tienes cierta experiencia como programador, sabes de los beneficios de usar un framework: escalabilidad, buenas prácticas, consistencia, funcionalidad, documentacion, etc. Pero en la vida real a veces nos topamos con proyectos hechos en PHP puro y surge la necesidad de agregarles funcionalidades pero éstas deberían hacerse siguiendo las buenas prácticas; desde la disposición de los archivos:

<a href="https://docs.php.earth/faq/misc/structure/" target="_blank">https://docs.php.earth/faq/misc/structure/</a>

hasta la implementación de librerías que son propias de frameworks pues eso le da más consistencia y aumenta su fiabilidad. En este ejemplo uso <a href="https://github.com/jenssegers/blade" target="_blank">Blade</a> como motor de templates (todos sabemos que Blade se usa con Laravel pero no todos saben que puedes usarlo externamente), <a href="https://alpinejs.dev/" target="_blank">Alpine JS</a> para darle reactividad al proyecto, Gulp para gestionar el live server también llamado livereload (si no conoces Gulp es similar a Webkit o Vite), <a href="https://github.com/rafaelwendel/phpsupabase" target="_blank">PHPSupabase</a> para interactuar con Supabase. Esta librería es muy buena, la única observación que le puedo hacer es que para hacer queries algo más complejos, carece de la implementación del condicional OR. La siguiente consulta es realizable:

```
SELECT * FROM tablename WHERE name LIKE "%$name%" AND address LIKE "%$address%"
```

pero lamentablemente esta otra ya no:

```
SELECT * FROM tablename WHERE name LIKE "%$str% OR surname LIKE "%$str%"
```

si necesitaran implementar el OR en sus queries pueden modificar el source si saben lo necesario.

Me olvidaba, para la interacción con el usuario: dialogs/alerts, toast y loader; uso mi proyecto <a href="https://github.com/oscaralderete/custom-web-elements" target="_blank">basado en Custom Web Elements</a> que no solo es compatible con proyectos web sino que también es posible usarlo con React, Svelte, Vue y cualquier otro framework JS.

## Instrucciones

1. Has de tener una cuenta en Supabase (es gratis), con un proyecto creado y una tabla llamada <b>users_</b> (la llamé así, porque el nombre 'users' la toma Supabase para su implementación Oauth).

2. La tabla 'users_' necesita 2 columnas: name, email. Te sugiero uses el editor que Supabase incorpora.

3. Copia el archivo <i>.env.sample</i> como <i>.env</i> y agrégale tus credenciales de Supabase.

4. Ejecuta los comandos:

```
composer install

npm install

gulp
```


