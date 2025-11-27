
# Videos Chapters & Logos

Video chapters & logos es un plugin ligero para WordPress que permite insertar videos MP4 en tus páginas o entradas, con las siguientes funcionalidades:

* Inserta videos con un shortcode `[video_extends]`.
* Permite agregar un logo sobre el video.
* Incluye marcadores de capítulos en el video.
* Soporta atributos como autoplay, loop, ancho, alto y poster.
* Preparado para traducciones.
* Carga el script de forma condicional solo si se usa el shortcode.


# instalación 

Video chapters & logos es un plugin ligero para WordPress que permite insertar videos MP4 en tus páginas o entradas, con las siguientes funcionalidades:

* Inserta videos con un shortcode `[video_extends]`.
* Permite agregar un logo sobre el video.
* Incluye marcadores de capítulos en el video.
* Soporta atributos como autoplay, loop, ancho, alto y poster.
* Preparado para traducciones.
* Carga el script de forma condicional solo si se usa el shortcode:

[videos_chapters_logos video="Sintel.mp4" width="800" height="450" autoplay="true" loop="true"]

[videos_chapters_logos video="Sintel.mp4" logo="logo.png" width="800" height="450" autoplay="true" loop="true" markers="0:39=Intro,5:50=Chapter 2,7:50=Chapter 3,9:00=Chapter 4"]












> **Nota:** Para que el plugin funcione correctamente, es necesario guardar las imágenes y los videos en las carpetas `img` y `video` dentro de la carpeta `assets` del proyecto. Asegúrate de respetar esta estructura para evitar errores al cargar los archivos.

[Documentación completa del plugin](https://github.com/alvinphp/documentation-video-extends/wiki)


# atributos de shortcode
* `video` (string) – Nombre del archivo de video (ubicado en `assets/video/`). Default: `Sintel.mp4`
* `logo` (string) – Nombre del archivo de logo (ubicado en `assets/img/`). Default: `example_logo.png`
* `poster` (string) – Imagen de poster antes de reproducir. Default: `poster.png`
* `width` (int) – Ancho del video. Default: `640`
* `height` (int) – Alto del video. Default: `360`
* `autoplay` (true/false) – Reproducción automática. Default: `false`
* `loop` (true/false) – Repetición automática. Default: `false`
* `id` (string) – ID del video. Default: generado automáticamente.

  # licencia y derecho de autor

Este plugin es un software libre y está bajo la licencia GNU LESSER GENERAL PUBLIC LICENSE (LGPL).
Copyright (C) 2025 Alvin Gil Saldaña. Todos los derechos reservados.
















```
    




