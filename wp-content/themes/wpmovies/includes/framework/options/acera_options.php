<?php

$mail_admin = get_option("admin_email");  $options = array(
/*============================================================================*/
array("type" => "section","icon" => "acera-icon-home","title" => __( "Configuration", "mundothemes" ),"id" => "general","expanded" => "true"),
array("type" => "section","icon" => "acera-icon-home","title" => __( "Ads options", "mundothemes" ),"id" => "anuncios","expanded" => "true"),
array("type" => "section","icon" => "acera-icon-home","title" => __( "Language / Edit front end text", "mundothemes" ),"id" => "idioma","expanded" => "true"),
# >> Ajuste Inicial
array("section" => "general", "type" => "heading","title" => __( "Initial Setup", "mundothemes" ),"id" => "general-config"),
array("section" => "general", "type" => "heading","title" => __( "SEO Slug Taxonomies", "mundothemes" ),"id" => "taxonomias-config"),
array("section" => "general", "type" => "heading","title" => __( "Forms / Add Movies", "mundothemes" ),"id" => "formularios-config"),
array("section" => "general", "type" => "heading","title" => __( "Style and colors / CSS", "mundothemes" ),"id" => "estilo-config"),
array("section" => "general", "type" => "heading","title" => __( "Comments", "mundothemes" ),"id" => "comentarios-config"),
array("section" => "general", "type" => "heading","title" => __( "Important notice Website", "mundothemes" ),"id" => "notice-config"),
# >> Ajuste Anuncios
array("section" => "anuncios", "type" => "heading","title" => __( "Ads Blocks", "mundothemes" ),"id" => "anuncios-config"),
# >> Ajuste de idioma
array("section" => "idioma", "type" => "heading","title" => __( "Home", "mundothemes" ),"id" => "texto-config-home"),
array("section" => "idioma", "type" => "heading","title" => __( "Form Add Movie", "mundothemes" ),"id" => "texto-config-addpost"),
array("section" => "idioma", "type" => "heading","title" => __( "Single Movies", "mundothemes" ),"id" => "texto-config-single"),

### Ajuste inicial
array(
    "under_section" => "taxonomias-config",
    "name" => __("Writers","mundothemes"),
    "id" => "escritor",
    "desc" => __("Caution: once defined this field can no longer edit it again", "mundothemes"),
    "default" => "escritor"
),
array(
    "under_section" => "taxonomias-config",
    "type" => "text",
    "name" => __("Year","mundothemes"),
    "id" => "year",
    "desc" => __("Caution: once defined this field can no longer edit it again", "mundothemes"),
    "default" => "fecha-estreno"
),
array(
    "under_section" => "taxonomias-config",
    "type" => "text",
    "name" => __("Quality","mundothemes"),
    "id" => "calidad",
    "desc" => __("Caution: once defined this field can no longer edit it again", "mundothemes"),
    "default" => "calidad"
),
array(
    "under_section" => "taxonomias-config",
    "type" => "text",
    "name" => __("Directors","mundothemes"),
    "id" => "director",
    "desc" => __("Caution: once defined this field can no longer edit it again", "mundothemes"),
    "default" => "director"
),
array(
    "under_section" => "taxonomias-config",
    "type" => "text",
    "name" => __("Cast","mundothemes"),
    "id" => "actor",
    "desc" => __("Caution: once defined this field can no longer edit it again", "mundothemes"),
    "default" => "actor"
),
array(
    "under_section" => "taxonomias-config",
    "type" => "text",
    "name" => __("news","mundothemes"),
    "id" => "news",
    "desc" => __("Caution: once defined this field can no longer edit it again", "mundothemes"),
    "default" => "news"
),
array(
    "under_section" => "taxonomias-config",
    "type" => "text",
    "name" => __("news/category","mundothemes"),
    "id" => "news-category",
    "desc" => __("Caution: once defined this field can no longer edit it again", "mundothemes"),
    "default" => __("news/category","mundothemes")
),




array(
    "under_section" => "taxonomias-config",
    "type" => "text",
    "name" => __("tvshows","mundothemes"),
    "id" => "tvshows",
    "desc" => __("Caution: once defined this field can no longer edit it again", "mundothemes"),
    "default" => "tvshows"
),
array(
    "under_section" => "taxonomias-config",
    "type" => "text",
    "name" => __("tvshows/category","mundothemes"),
    "id" => "tvshows-category",
    "desc" => __("Caution: once defined this field can no longer edit it again", "mundothemes"),
    "default" => __("tvshows/category","mundothemes")
),

array(
    "under_section" => "taxonomias-config",
    "type" => "text",
    "name" => __("episode","mundothemes"),
    "id" => "episode",
    "desc" => __("Caution: once defined this field can no longer edit it again", "mundothemes"),
    "default" => __("episode","mundothemes")
),





array(
    "under_section" => "general-config",
    "type" => "image",
    "placeholder" => __("Upload logo","mundothemes"),
    "name" => __("Upload logo","mundothemes"),
    "id" => "general-logo",
    "default" => ""),
array(
    "under_section" => "general-config",
    "type" => "image",
    "placeholder" => __("Upload logo responsive","mundothemes"),
    "name" => __("Upload logo responsive","mundothemes"),
    "id" => "general-logo-responsive",
    "default" => ""),


array(
    "under_section" => "general-config",
    "type" => "image",
    "placeholder" => __("Upload favicon","mundothemes"),
    "name" => __("Upload favicon","mundothemes"),
    "id" => "general-favicon",
    "desc" => __("Add favicon, preferably in .ico format","mundothemes"),
    "default" => ""),

array(
    "under_section" => "general-config",
    "type" => "checkbox",
    "name" => __("Infinite Scroll", "mundothemes"),
    "id" => array("activar-is"),
    "options" => array( __("Activate Infinite Scroll?","mundothemes"), ),
    "desc" => __("check to activate", "mundothemes"),
    "default" => array("not")),

array(
    "under_section" => "general-config",
    "type" => "checkbox",
    "name" => __("Movie Mode", "mundothemes"),
    "id" => array("activar-pelicula"),
    "options" => array( __("Want to activate the multiplayer to show movies online","mundothemes"), ),
    "desc" => __("Active to show movies online", "mundothemes"),
    "default" => array("not")),

array(
    "under_section" => "general-config",
    "type" => "checkbox",
    "name" => __("Show downloads","mundothemes"),
    "id" => array("activar-descargas"),
    "options" => array( __("Want to activate the downloads module?","mundothemes"), ),
    "desc" => __("Active to display module downloads", "mundothemes"),
    "default" => array("not")),

array(
    "under_section" => "general-config",
    "type" => "checkbox",
    "name" => __("Module releases","mundothemes"),
    "id" => array("activar-estrenos"),
    "options" => array( __("Want to display the module releases?", "mundothemes"), ),
    "desc" => __("Now set the premieres category", "mundothemes"),
    "default" => array("not")),


array(
    "under_section" => "general-config", //Required
    "type" => "text", //Required
    "name" => __("Category Releases","mundothemes"), //Required
    "id" => "estrenos_cat", //Required
    "placeholder" => __("Numerical category ID premieres", "mundothemes"),
    "desc" => __("Enter the numeric ID of the category premieres or similar", "mundothemes"),
    "default" => ""
),
array(
    "under_section" => "general-config-back",
    "type" => "checkbox",
    "name" => __("Module News","mundothemes"),
    "id" => array("activar-noticias"),
    "options" => array( __("Want to turn news module?", "mundothemes"), ),
    "desc" => __("After activating you can now post news", "mundothemes"),
    "default" => array("not")),


array(
    "under_section" => "general-config-back", //Required
    "type" => "text", //Required
    "name" => __( "URL Search Advanced", "mundothemes" ),  //Required
    "display_checkbox_id" => "toggle_checkbox_id",
    "id" => "busqueda_a", //Required
    "placeholder" => __( "http://", "mundothemes" ),
    "desc" => __( "Add a link to the advanced search page", "mundothemes" ),
),

array(
    "under_section" => "general-config", //Required
    "type" => "textarea", //Required
    "name" => __( "Google Analytics code", "mundothemes" ), //Required
    "id" => "analitica", //Required
    "display_checkbox_id" => "toggle_checkbox_id",
    "desc" => __( "Make your tracking code Google Analytics.", "mundothemes" ),
    "default" => ""
),
array(
    "under_section" => "general-config", //Required
    "type" => "textarea", //Required
    "name" => __( "Extra integration code", "mundothemes" ), //Required
    "id" => "code_integracion", //Required
    "display_checkbox_id" => "toggle_checkbox_id",
    "desc" => __( "Make your HTML integration with the theme code.", "mundothemes" ),
    "default" => ""
),

array(
    "under_section" => "general-config-back", //Required
    "type" => "text", //Required
    "name" => __( "Facebook", "mundothemes" ), //Required
    "display_checkbox_id" => "toggle_checkbox_id",
    "id" => "fb_url", //Required
    "placeholder" => "Facebook URL",
    "desc" => __( "", "mundothemes" ),
    "default" => ""
),

array(
    "under_section" => "general-config-back", //Required
    "type" => "text", //Required
    "name" => __( "Twitter", "mundothemes" ), //Required
    "display_checkbox_id" => "toggle_checkbox_id",
    "id" => "twt_url", //Required
    "placeholder" => "Twitter URL",
    "desc" => __( "", "mundothemes" ),
    "default" => ""
),
array(
    "under_section" => "general-config-back", //Required
    "type" => "text", //Required
    "name" => __( "Google+", "mundothemes" ), //Required
    "display_checkbox_id" => "toggle_checkbox_id",
    "id" => "gogl_url", //Required
    "placeholder" => "Google+ URL",
    "desc" => __( "", "mundothemes" ),
    "default" => ""
),
array(
    "under_section" => "general-config-back", //Required
    "type" => "select", //Required
    "name" => __("Entries per page in categories", "mundothemes"), //Required
    "display_checkbox_id" => "toggle_checkbox_id",
    "id" => "nu-categorias", //Required
    "options" => array("12", "18", "24", "30", "36", "42", "48", "54", "60", "66"), //Required
    "desc" => __("Maximum number of entries to display in the categories", "mundothemes"),
    "default" => "12"),
array(
    "under_section" => "general-config-back", //Required
    "type" => "select", //Required
    "name" => __("Entries per page on search results", "mundothemes"), //Required
    "display_checkbox_id" => "toggle_checkbox_id",
    "id" => "nu-busqueda", //Required
    "options" => array("5", "6", "7", "10", "15", "20", "25", "30", "35", "40"), //Required
    "desc" => __("Maximum number of entries to display in the search results", "mundothemes"),
    "default" => "5"),

array(
    "under_section" => "general-config-off", //Required
    "type" => "text", //Required
    "name" => __( "Copyright Footer Text", "mundothemes" ), //Required
    "id" => "brand", //Required
	"placeholder" => " ",
    "display_checkbox_id" => "toggle_checkbox_id",
    "desc" => __( "Your license allows you to modify the copyright", "mundothemes" ),
    "default" => ""
),

### Estilo y colores
array(
    "under_section" => "estilo-config-back",
    "type" => "checkbox",
    "name" => __("Dark Style","mundothemes"),
    "id" => array("activar-dark"),
    "options" =>array( __("want to activate the dark style?","mundothemes"), ),
	"desc" => __("Enable Dark Style", "mundothemes"),
    "default" => array("not")),

array(
    "under_section" => "estilo-config",
    "type" => "checkbox",
    "name" => __("Allow change the main colors","mundothemes"),
    "id" => array("activar-main-color"),
    "options" =>array( __("Enable change of main colors?","mundothemes"), ),
	"desc" => __("Active to show changes", "mundothemes"),
    "default" => array("not")),


array(
    "under_section" => "estilo-config", //Required
    "type" => "color", //Required
    "name" => __("Main color","mundothemes"), //Required
    "id" => "color_alfa", //Required
    "desc" => __("Choose a color","mundothemes"),
    "default" => "13a9ff"
),
array(
    "under_section" => "estilo-config", //Required
    "type" => "color", //Required
    "name" => __("Main background color","mundothemes"), //Required
    "id" => "color_alfa_bg", //Required
    "desc" => __("Choose a color","mundothemes"),
    "default" => "343C48"
),
array(
    "under_section" => "estilo-config", //Required
    "type" => "color", //Required
    "name" => __("Color input focus","mundothemes"), //Required
    "id" => "input_focus", //Required
    "desc" => __("Choose a color","mundothemes"),
    "default" => "13a9ff"
),
array(
    "under_section" => "estilo-config",
    "type" => "checkbox",
    "name" => __("Active custom CSS","mundothemes"),
    "id" => array("activar_css"),
    "options" =>array( __("Want to enable custom CSS code?","mundothemes"), ),
	"desc" => __("Enable custom CSS code", "mundothemes"),
    "default" => array("not")),
array(
    "under_section" => "estilo-config", //Required
    "type" => "textarea",
    "name" => __( "Add custom CSS", "mundothemes" ), //Required
    "id" => "code_css", //Required
    "display_checkbox_id" => "toggle_checkbox_id",
    "desc" => __( "Add only CSS code", "mundothemes" ),
),
### Configuracion de comentarios
array(
    "under_section" => "comentarios-config",
    "type" => "checkbox",
    "name" => __("Enable comments on pages", "mundothemes"),
    "id" => array("activar-com-pages"),
    "options" => array( __("You want to display comments on the pages","mundothemes"), ),
    "desc" => __("Active to show", "mundothemes"),
    "default" => array("not")
),
array(
    "under_section" => "comentarios-config",
    "type" => "checkbox",
    "name" => __("Enable comments in movies", "mundothemes"),
    "id" => array("activar-com-single"),
    "options" => array( __("You want to display comments in the movies","mundothemes"), ),
    "desc" => __("Active to show", "mundothemes"),
    "default" => array("checked")
),

array(
    "under_section" => "comentarios-config",
    "type" => "checkbox",
    "name" => __("Facebook comments", "mundothemes"),
    "id" => array("activar-facebook"),
    "options" => array( __("Enable comments with facebook","mundothemes"), ),
    "desc" => __("It is recommended to continue with the rest of the configuration", "mundothemes"),
    "default" => array("not")
),
array(
    "under_section" => "comentarios-config", //Required
    "type" => "text", //Required
    "name" => __( "Facebook App ID", "mundothemes" ), //Required
    "display_checkbox_id" => "toggle_checkbox_id",
    "id" => "fb_id", //Required
    "placeholder" => "209955335852854",
    "desc" => __( "Add the ID of your facebook application", "mundothemes" ),
    "default" => ""
),


array(
    "under_section" => "comentarios-config", //Required
    "type" => "text", //Required
    "name" => __( "Facebook Profile ID admin", "mundothemes" ), //Required
    "display_checkbox_id" => "toggle_checkbox_id",
    "id" => "fb_id_admin", //Required
    "placeholder" => "1623799548",
    "desc" => __( "Adds the id of your facebook profile to moderate comments", "mundothemes" ),
    "default" => ""
),


array(
    "under_section" => "comentarios-config", //Required
    "type" => "text", //Required
    "name" => __( "Facebook App language", "mundothemes" ), //Required
    "display_checkbox_id" => "toggle_checkbox_id",
    "id" => "fb_idioma", //Required
    "placeholder" => "en_EN",
    "desc" => __( "Add the language code you want, (es_LA, ro_RO, pt_BR)", "mundothemes" ),
    "default" => ""
),



array(
    "under_section" => "comentarios-config", //Required
    "type" => "select", //Required
    "name" => __("Facebook Color Scheme", "mundothemes"), //Required
    "display_checkbox_id" => "toggle_checkbox_id",
    "id" => "fb_color", //Required
    "options" => array("light", "dark"), //Required
    "desc" => __("Choose the color for the comment block", "mundothemes"),
    "default" => ""),

array(
    "under_section" => "comentarios-config", //Required
    "type" => "select", //Required
    "name" => __("Facebook Number of Posts", "mundothemes"), //Required
    "display_checkbox_id" => "toggle_checkbox_id",
    "id" => "fb_numero", //Required
    "options" => array("5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15"), //Required
    "desc" => __("Select number of comments to display per publication", "mundothemes"),
    "default" => ""),

array(
    "under_section" => "comentarios-config",
    "type" => "checkbox",
    "name" => __("Manage comments Disqus", "mundothemes"),
    "id" => array("activar-disqus"),
    "options" => array( __("Enable comments Disqus","mundothemes"), ),
    "desc" => __("Remember to add the shortname of your community with disqus", "mundothemes"),
    "default" => array("not")
),
array(
    "under_section" => "comentarios-config", //Required
    "type" => "text", //Required
    "name" => __( "Shorname Disqus", "mundothemes" ), //Required
    "display_checkbox_id" => "toggle_checkbox_id",
    "id" => "disqus_id", //Required
    "placeholder" => "grifus",
    "desc" => __( "Add your community shortname Disqus", "mundothemes" ),
    "default" => ""
),

### Configuracion de Notificaciones
array(
    "under_section" => "notice-config",
    "type" => "checkbox",
    "name" => __("Activate module", "mundothemes"),
    "id" => array("activar_notice"),
    "options" => array( __("You want to activate the notices module?","mundothemes"), ),
    "desc" => __("Active to display module", "mundothemes"),
    "default" => array("not")
),

array(
    "under_section" => "notice-config", //Required
    "type" => "textarea",
    "name" => __( "Add notice", "mundothemes" ), //Required
    "id" => "notice", //Required
    "display_checkbox_id" => "toggle_checkbox_id",
    "desc" => __( "Add notice, this field accepts HTML", "mundothemes" ),
),

array(
    "under_section" => "notice-config", //Required
    "type" => "color", //Required
    "name" => __("Notice Background color","mundothemes"), //Required
    "id" => "color_notice", //Required
    "desc" => __("Choose a color","mundothemes"),
    "default" => "98B659"
),

array(
    "under_section" => "notice-config", //Required
    "type" => "text", //Required
    "name" => __( "Cookie name", "mundothemes" ), //Required
    "display_checkbox_id" => "toggle_checkbox_id",
    "id" => "cookie_name", //Required
    "placeholder" =>  __( "Cookie name", "mundothemes" ),
    "desc" => __( "Add name cookie, rename each time you make a new notice", "mundothemes" ),
    "default" => "cookiename"
),

array(
    "under_section" => "notice-config", //Required
    "type" => "select", //Required
    "name" => __("Cookie expiration", "mundothemes"), //Required
    "display_checkbox_id" => "toggle_checkbox_id",
    "id" => "cookie_exp", //Required
    "options" => array("1", "7", "15", "30", "60", "180", "365"), //Required
    "desc" => __("Select the number of days for Cookie", "mundothemes"),
    "default" => "7"),



### Configuracion de formularios

array(
    "under_section" => "formularios-config-back",
    "type" => "checkbox",
    "name" => __("Email report new entry", "mundothemes"),
    "id" => array("email_newpost"),
    "options" => array( __("Want to be notified of a new entry?","mundothemes"), ),
    "desc" => __("Active to send notification", "mundothemes"),
    "default" => array("not")
),

array(
    "under_section" => "formularios-config-back", //Required
    "type" => "text", //Required
    "name" => __( "E-mail", "mundothemes" ),  //Required
    "display_checkbox_id" => "toggle_checkbox_id",
    "id" => "email_c", //Required
    "placeholder" => __( "E-mail", "mundothemes" ),
    "desc" => __( "Exclusive to the data sent forms of theme.", "mundothemes" ),
    "default" => $mail_admin,
),
array(
    "under_section" => "formularios-config", //Required
    "type" => "text", //Required
    "name" => __("reCAPTCHA Public Key","mundothemes"), //Required
    "display_checkbox_id" => "toggle_checkbox_id",
    "id" => "public_key_rcth", //Required
    "placeholder" => __("Public Key reCAPTCHA","mundothemes"),
    "desc" => "",
    "default" => ""
),
array(
    "under_section" => "formularios-config", //Required
    "type" => "text", //Required
    "name" => __("reCAPTCHA Private Key","mundothemes"), //Required
    "display_checkbox_id" => "toggle_checkbox_id",
    "id" => "private_key_rcth", //Required
    "placeholder" => __("Private Key reCAPTCHA","mundothemes"),
    "desc" => "",
    "default" => ""
),

array(
    "under_section" => "formularios-config", //Required
    "type" => "text", //Required
    "name" => __( "URL Add movie", "mundothemes" ),  //Required
    "display_checkbox_id" => "toggle_checkbox_id",
    "id" => "add_movie", //Required
    "placeholder" => __( "http://", "mundothemes" ),
    "desc" => __( "Add a link to the Add movie page", "mundothemes" ),
),

array(
    "under_section" => "formularios-config",
    "type" => "checkbox",
    "name" => __("Enable wordpress editor", "mundothemes"),
    "id" => array("activar-editor"),
    "options" => array( __("You want to show editor WordPress?","mundothemes"), ),
    "desc" => __("Enable to show", "mundothemes"),
    "default" => array("not")
),


### bloques de anuncios disponibles
array(
    "under_section" => "anuncios-config",
    "type" => "checkbox",
    "name" => __("Block ad 728x90", "mundothemes"),
    "id" => array("activar-anuncio-728-90"),
    "options" => array( __("Show block 728x90 ad","mundothemes"), ),
    "desc" => __("Active to show ad", "mundothemes"),
    "default" => array("not")
),
array(
    "under_section" => "anuncios-config", //Required
    "type" => "textarea", //Required
    "name" => __( "728x90 ad code", "mundothemes" ), //Required
    "id" => "anuncio-728-90", //Required
    "display_checkbox_id" => "toggle_checkbox_id",
    "desc" => __( "Add HTML code", "mundothemes" ),
    "default" => ""
),
array(
    "under_section" => "anuncios-config",
    "type" => "checkbox",
    "name" => __("Block ad 300x250", "mundothemes"),
    "id" => array("activar-anuncio-300-250"),
    "options" => array( __("Show block 300x250 ad","mundothemes"), ),
    "desc" => __("Active to show ad", "mundothemes"),
    "default" => array("not")
),
array(
    "under_section" => "anuncios-config", //Required
    "type" => "textarea", //Required
    "name" => __( "300x250 ad code", "mundothemes" ), //Required
    "id" => "anuncio-300-250", //Required
    "display_checkbox_id" => "toggle_checkbox_id",
    "desc" => __( "Add HTML code", "mundothemes" ),
    "default" => ""
),

################## Textos Front End ##################
array(
    "under_section" => "texto-config-home", //Required
    "type" => "text", //Required
    "name" => __("Main","mundothemes"), //Required
    "id" => "text-1", //Required
    "placeholder" => __("Main","mundothemes"),
),

array(
    "under_section" => "texto-config-home", //Required
    "type" => "text", //Required
    "name" => __("Genre","mundothemes"), //Required
    "id" => "text-2", //Required
    "placeholder" => __("Genre","mundothemes"),
),

array(
    "under_section" => "texto-config-home", //Required
    "type" => "text", //Required
    "name" => __("Added recently","mundothemes"), //Required
    "id" => "text-8", //Required
    "placeholder" => __("Added recently","mundothemes"),
),

array(
    "under_section" => "texto-config-home", //Required
    "type" => "text", //Required
    "name" => __("Most viewed","mundothemes"), //Required
    "id" => "text-3", //Required
    "placeholder" => __("Most viewed","mundothemes"),
),

array(
    "under_section" => "texto-config-home", //Required
    "type" => "text", //Required
    "name" => __("Most voted","mundothemes"), //Required
    "id" => "text-4", //Required
    "placeholder" => __("Most voted","mundothemes"),
),

array(
    "under_section" => "texto-config-home", //Required
    "type" => "text", //Required
    "name" => __("News","mundothemes"), //Required
    "id" => "text-5", //Required
    "placeholder" => __("News","mundothemes"),
),

array(
    "under_section" => "texto-config-home", //Required
    "type" => "text", //Required
    "name" => __("Search..","mundothemes"), //Required
    "id" => "text-6", //Required
    "placeholder" => __("Search..","mundothemes"),
),

array(
    "under_section" => "texto-config-home", //Required
    "type" => "text", //Required
    "name" => __("Add movies","mundothemes"), //Required
    "id" => "text-7", //Required
    "placeholder" => __("Add movies","mundothemes"),
),


array(
    "under_section" => "texto-config-home", //Required
    "type" => "text", //Required
    "name" => __("All movies","mundothemes"), //Required
    "id" => "text-9", //Required
    "placeholder" => __("All movies","mundothemes"),
),

array(
    "under_section" => "texto-config-home", //Required
    "type" => "text", //Required
    "name" => __("Like us on facebook.","mundothemes"), //Required
    "id" => "text-10", //Required
    "placeholder" => __("Like us on facebook.","mundothemes"),
),

array(
    "under_section" => "texto-config-home", //Required
    "type" => "text", //Required
    "name" => __("20 Most viewed","mundothemes"), //Required
    "id" => "text-11", //Required
    "placeholder" => __("20 Most viewed","mundothemes"),
),

array(
    "under_section" => "texto-config-home", //Required
    "type" => "text", //Required
    "name" => __("20 Most voted","mundothemes"), //Required
    "id" => "text-12", //Required
    "placeholder" => __("20 Most voted","mundothemes"),
),

array(
    "under_section" => "texto-config-home", //Required
    "type" => "text", //Required
    "name" => __("No content available","mundothemes"), //Required
    "id" => "text-13", //Required
    "placeholder" => __("No content available","mundothemes"),
),

array(
    "under_section" => "texto-config-home", //Required
    "type" => "text", //Required
    "name" => __("Error 404","mundothemes"), //Required
    "id" => "text-14", //Required
    "placeholder" => __("Error 404","mundothemes"),
),
array(
    "under_section" => "texto-config-home", //Required
    "type" => "text", //Required
    "name" => __("Results","mundothemes"), //Required
    "id" => "text-29", //Required
    "placeholder" => __("Results","mundothemes"),
),
array(
    "under_section" => "texto-config-home", //Required
    "type" => "text", //Required
    "name" => __("No results available","mundothemes"), //Required
    "id" => "text-30", //Required
    "placeholder" => __("No results available","mundothemes"),
),



array(
    "under_section" => "texto-config-addpost", //Required
    "type" => "text", //Required
    "name" => __("Add new entry","mundothemes"), //Required
    "id" => "text-15", //Required
    "placeholder" => __("Add new entry","mundothemes"),
),

array(
    "under_section" => "texto-config-addpost", //Required
    "type" => "text", //Required
    "name" => __("Add all required fields.","mundothemes"), //Required
    "id" => "text-16", //Required
    "placeholder" => __("Add all required fields.","mundothemes"),
),

array(
    "under_section" => "texto-config-addpost", //Required
    "type" => "text", //Required
    "name" => __("Excellent, the data has been sent.","mundothemes"), //Required
    "id" => "text-17", //Required
    "placeholder" => __("Excellent, the data has been sent.","mundothemes"),
),

array(
    "under_section" => "texto-config-addpost", //Required
    "type" => "text", //Required
    "name" => __("Your publication could not be processed","mundothemes"), //Required
    "id" => "text-18", //Required
    "placeholder" => __("Your publication could not be processed","mundothemes"),
),

array(
    "under_section" => "texto-config-addpost", //Required
    "type" => "text", //Required
    "name" => __("Try again","mundothemes"), //Required
    "id" => "text-19", //Required
    "placeholder" => __("Try again","mundothemes"),
),

array(
    "under_section" => "texto-config-addpost", //Required
    "type" => "text", //Required
    "name" => __("Original title","mundothemes"), //Required
    "id" => "text-20", //Required
    "placeholder" => __("Original title","mundothemes"),
),

array(
    "under_section" => "texto-config-addpost", //Required
    "type" => "text", //Required
    "name" => __("Add title of the movie.","mundothemes"), //Required
    "id" => "text-21", //Required
    "placeholder" => __("Add title of the movie.","mundothemes"),
),

array(
    "under_section" => "texto-config-addpost", //Required
    "type" => "text", //Required
    "name" => __("Synopsis","mundothemes"), //Required
    "id" => "text-28", //Required
    "placeholder" => __("Synopsis","mundothemes"),
),

array(
    "under_section" => "texto-config-addpost", //Required
    "type" => "text", //Required
    "name" => __("Add an abstract of no more than 1000 characters of the synopsis or plot.","mundothemes"), //Required
    "id" => "text-22", //Required
    "placeholder" => __("Add an abstract of no more than 1000 characters of the synopsis or plot.","mundothemes"),
),

array(
    "under_section" => "texto-config-addpost", //Required
    "type" => "text", //Required
    "name" => __("IMDb id","mundothemes"), //Required
    "id" => "text-23", //Required
    "placeholder" => __("IMDb id","mundothemes"),
),

array(
    "under_section" => "texto-config-addpost", //Required
    "type" => "text", //Required
    "name" => __("Assign ID IMDb, example URL = http://www.imdb.com/title/<i>tt0120338</i>/","mundothemes"), //Required
    "id" => "text-24", //Required
    "placeholder" => __("Assign ID IMDb, example URL = http://www.imdb.com/title/<i>tt0120338</i>/","mundothemes"),
),

array(
    "under_section" => "texto-config-addpost", //Required
    "type" => "text", //Required
    "name" => __("Upload poster image.","mundothemes"), //Required
    "id" => "text-25", //Required
    "placeholder" => __("Upload poster image.","mundothemes"),
),

array(
    "under_section" => "texto-config-addpost", //Required
    "type" => "text", //Required
    "name" => __("Select main genres of film.","mundothemes"), //Required
    "id" => "text-26", //Required
    "placeholder" => __("Select main genres of film.","mundothemes"),
),

array(
    "under_section" => "texto-config-addpost", //Required
    "type" => "text", //Required
    "name" => __("Send content","mundothemes"), //Required
    "id" => "text-27", //Required
    "placeholder" => __("Send content","mundothemes"),
),

array(
    "under_section" => "texto-config-single", //Required
    "type" => "text", //Required
    "name" => __("Download Links","mundothemes"), //Required
    "id" => "text-31", //Required
    "placeholder" => __("Download Links","mundothemes"),
),

array(
    "under_section" => "texto-config-single", //Required
    "type" => "text", //Required
    "name" => __("View Online","mundothemes"), //Required
    "id" => "text-32", //Required
    "placeholder" => __("View Online","mundothemes"),
),

array(
    "under_section" => "texto-config-single", //Required
    "type" => "text", //Required
    "name" => __("Server","mundothemes"), //Required
    "id" => "text-33", //Required
    "placeholder" => __("Server","mundothemes"),
),

array(
    "under_section" => "texto-config-single", //Required
    "type" => "text", //Required
    "name" => __("Audio / Language","mundothemes"), //Required
    "id" => "text-34", //Required
    "placeholder" => __("Audio / Language","mundothemes"),
),

array(
    "under_section" => "texto-config-single", //Required
    "type" => "text", //Required
    "name" => __("Quality","mundothemes"), //Required
    "id" => "text-35", //Required
    "placeholder" => __("Quality","mundothemes"),
),

array(
    "under_section" => "texto-config-single", //Required
    "type" => "text", //Required
    "name" => __("Option","mundothemes"), //Required
    "id" => "text-36", //Required
    "placeholder" => __("Option","mundothemes"),
),

array(
    "under_section" => "texto-config-single", //Required
    "type" => "text", //Required
    "name" => __("No links available","mundothemes"), //Required
    "id" => "text-37", //Required
    "placeholder" => __("No links available","mundothemes"),
),

array(
    "under_section" => "texto-config-single", //Required
    "type" => "text", //Required
    "name" => __("No sources available","mundothemes"), //Required
    "id" => "text-38", //Required
    "placeholder" => __("No sources available","mundothemes"),
),

array(
    "under_section" => "texto-config-single", //Required
    "type" => "text", //Required
    "name" => __("Share","mundothemes"), //Required
    "id" => "text-39", //Required
    "placeholder" => __("Share","mundothemes"),
),

array(
    "under_section" => "texto-config-single", //Required
    "type" => "text", //Required
    "name" => __("Tweet","mundothemes"), //Required
    "id" => "text-40", //Required
    "placeholder" => __("Tweet","mundothemes"),
),

array(
    "under_section" => "texto-config-single", //Required
    "type" => "text", //Required
    "name" => __("Season","mundothemes"), //Required
    "id" => "text-41", //Required
    "placeholder" => __("Season","mundothemes"),
),

array(
    "under_section" => "texto-config-single", //Required
    "type" => "text", //Required
    "name" => __("Episode","mundothemes"), //Required
    "id" => "text-42", //Required
    "placeholder" => __("Episode","mundothemes"),
),

array(
    "under_section" => "texto-config-single", //Required
    "type" => "text", //Required
    "name" => __("No episodes","mundothemes"), //Required
    "id" => "text-43", //Required
    "placeholder" => __("No episodes","mundothemes"),
),

array(
    "under_section" => "texto-config-single", //Required
    "type" => "text", //Required
    "name" => __("No seasons","mundothemes"), //Required
    "id" => "text-44", //Required
    "placeholder" => __("No seasons","mundothemes"),
),

array(
    "under_section" => "texto-config-single", //Required
    "type" => "text", //Required
    "name" => __("More TV Shows","mundothemes"), //Required
    "id" => "text-45", //Required
    "placeholder" => __("More TV Shows","mundothemes"),
),

array(
    "under_section" => "texto-config-single", //Required
    "type" => "text", //Required
    "name" => __("More movies","mundothemes"), //Required
    "id" => "text-46", //Required
    "placeholder" => __("More movies","mundothemes"),
),

array(
    "under_section" => "texto-config-home", //Required
    "type" => "text", //Required
    "name" => __("Release Year","mundothemes"), //Required
    "id" => "text-48", //Required
    "placeholder" => __("Release Year","mundothemes"),
),

array(
    "under_section" => "texto-config-home", //Required
    "type" => "text", //Required
    "name" => __("All TV Shows","mundothemes"), //Required
    "id" => "text-49", //Required
    "placeholder" => __("All TV Shows","mundothemes"),
),

array(
    "under_section" => "texto-config-single", //Required
    "type" => "text", //Required
    "name" => __("Go back","mundothemes"), //Required
    "id" => "text-50", //Required
    "placeholder" => __("Go back","mundothemes"),
),
array(
    "under_section" => "texto-config-home", //Required
    "type" => "text", //Required
    "name" => __("Recommended TV Shows","mundothemes"), //Required
    "id" => "text-51", //Required
    "placeholder" => __("Recommended TV Shows","mundothemes"),
),

array(
    "under_section" => "texto-config-home", //Required
    "type" => "text", //Required
    "name" => __("Latest TV Shows","mundothemes"), //Required
    "id" => "text-52", //Required
    "placeholder" => __("Latest TV Shows","mundothemes"),
),

array(
    "under_section" => "texto-config-home", //Required
    "type" => "text", //Required
    "name" => __("New Releases","mundothemes"), //Required
    "id" => "text-53", //Required
    "placeholder" => __("New Releases","mundothemes"),
),

array(
    "under_section" => "texto-config-home", //Required
    "type" => "text", //Required
    "name" => __("Recommended movies","mundothemes"), //Required
    "id" => "text-54", //Required
    "placeholder" => __("Recommended movies","mundothemes"),
),

array(
    "under_section" => "texto-config-home", //Required
    "type" => "text", //Required
    "name" => __("Latest movies","mundothemes"), //Required
    "id" => "text-55", //Required
    "placeholder" => __("Latest movies","mundothemes"),
),


/*===================================25/02/2015=========================================*/
    );
?>