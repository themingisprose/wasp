# WASP 🐝
### Woew! Another starter plugin

**WASP** es un _starter_ plugin que facilita el desarrollo con WordPress.
**WASP** tiene soporte para **WPML**.

## Elementos y Clases
- Página de administración `class WASP_Admin_Page`
- Settings Fields `abstract class WASP_Setting_Fields`
- Subpágina de administración `abstract class WASP_Admin_Sub_Page`
- Custom Post Types `abstract class WASP_Custom_Post_Type`
- Meta Boxes `abstract class WASP_Meta_Box`
- Taxonomies `abstract class WASP_Taxonomy`
- Enqueue Scripts `class WASP_Enqueue`


## Página de Administración
Para habilitar la página de administración hay que establecer como `true` el filtro `wasp_enable_admin_page`

```php
add_filter( 'wasp_enable_admin_page', '__return_true' );
```

## Settings Fields
Podemos agregar campos a la **Página de Administración** extendiendo la clase `WASP_Setting_Fields` y declarando el método `fields()` de dicha clase.

### fields()
Este método debe retornar un array asociativo.

```php
class My_Class_Of_Fields extends WASP_Setting_Fields
{

	public function fields()
	{
        /**
         * 'label' string		Nombre del campo
         * 'option' string		Nombre a guardar en la base de datos
         * 'type' string		Tipo de campo: 'text', 'url', 'email', 'textarera', 'content',
         * 'lang' array			Array vacío
         */
		$fields = array(
			'field_a'	=> array(
				'label'		=> __( 'Title', 'text-domain' ),
				'option'	=> 'field_a',
				'type'		=> 'text',
				'lang'		=> array(),
			),
			...
		);
		/**
		 * Filters the fields
		 * @param array $fields
		 */
		return apply_filters( 'my_class_of_fields_filters_fields', $fields );
	}
}
```
### __construct() & init()
Se debe inicializar la clase pasando los siguientes parámetros al constructor y luego llamar al método `init()`:
```php
/**
 * @param string $section_id 	HTML section id
 * @param string $section_title 	Section title
 * @param string $field_id 		HTML field id
 * @param string $field_title 	Field title
 * @param string $wpml_field 	Name of the filter returned by method fields()
 */
$init = new My_Class_Of_Fields(
			'my-section-id',
			__( 'My section title', 'text-domain' ),
			'my-field-id',
			__( 'My field title', 'text-domain' ),
			'my_class_of_fields_filters_fields'
		);
$init->init();
```

## Subpágina de administración
En caso que sea necesario, se puede crear una subpágina de administración extendiendo la clase `WASP_Admin_Sub_Page`.

### __construct() & init()
Se debe inicializar la clase pasando los siguientes parámetros al constructor y luego llamar al método `init()`:
```php
class My_Class_Admin_Sub_Page extends WASP_Admin_Sub_Page
{

	function __construct()
	{
		$parent = new WASP_Admin_Page;

		$this->parent_slug		= $parent->slug;
		$this->page_title		= __( 'The WASP Subpage', 'wasp' );
		$this->menu_title		= __( 'The WASP Subpage', 'wasp' );
		$this->dashboard_title	= __( 'The WASP Subpage', 'wasp' );
		$this->menu_slug		= $parent->slug .'-the-wasp-sp';

		$this->option_group		= 'the_wasp_sp_setting';
		$this->option_name		= 'the_wasp_sp_options';
	}
}
$init = new My_Class_Admin_Sub_Page();
$init->init();
```

### Agregar campos a la Subpágina de administración
En este caso se debe crear una clase abstracta que extienda de `WASP_Setting_Fields`
```php
abstract class Subpage_Setting_Fields extends WASP_Setting_Fields
{

	function __construct( $section_id = '', $section_title = '', $field_id = '', $field_title = '', $wpml_field = null )
	{
		$admin = new My_Class_Admin_Sub_Page;
		$this->slug 			= $admin->menu_slug;
		$this->option_group 	= $admin->option_group;
		$this->option_name 		= $admin->option_name;

		$this->section_id 		= $section_id;
		$this->section_title 	= $section_title;
		$this->field_id 		= $field_id;
		$this->field_title 		= $field_title;
		$this->wpml_field 		= $wpml_field;
	}
}
```
Finalmente creamos la clase que va a imprimir los campos en pantalla, que a su vez extiende de `Subpage_Setting_Fields`.
```php
class My_Class_Subpage_Content extends My_Class_Subpage_Setting_Fields
{

	function fields()
	{
		$fields = array(
			'field_a'	=> array(
				'label'		=> __( 'Field Label', 'text-domain' ),
				'option'	=> 'field_a',
				'type'		=> 'text',
				'lang'		=> array()
			),
			...
		);

		return $fields;
	}
}
$init = new My_Class_Subpage_Content(
				'section-section-id',
				__( 'Section', 'wasp'),
				'section-field-id',
				__( 'Section Field', 'wasp')
			);
$init->init();
```

## Custom Post Types
Para crear nuesvos post types es necesario crear una clase que extienda de `WASP_Custom_Post_Type`. En dicha clase debemos declarar el constructor con las siguientes propiedades:
```php
class My_Class_CPT extends WASP_Custom_Post_Type
{

	function __construct()
	{
		// CPT slug
		$this->post_type = 'my-cpt-slug';

		// CPT labels
		$this->labels = array( ... );

		// CPT arguments
		$this->args = array( ... );
	}
}
```
### init()
```php
$init = new My_Class_CPT;
$init->init();
```
Ver documentación sobre **Custom Post Types** https://developer.wordpress.org/reference/functions/register_post_type/

## Meta Boxes

Podemos agregar **Meta Boxes** a los diferentes post types creando una nueva clase y extendiendo de `WASP_Meta_Box` y delcarando el método `fields()` de dicha clase.

### fields()
Este método debe retornar un array asociativo:
```php
class My_Class_Post_Meta_box extends WASP_Meta_Box
{

	function fields()
	{
		$fields = array(
			'field_a'	=> array(
				'label'	=> __( 'Field A Title', 'text-domain' ),
				'meta'	=> 'field_a',
			),
			'field_b'	=> array(
				'label'	=> __( 'Field B Title', 'text-domain' ),
				'meta'	=> 'field_b',
			),
		);

		return $fields;
	}
}
```
### __construct() & init()
Se debe inicializar la clase pasando los siguientes parámetros al constructor y luego llamar al método `init()`:
```php
/**
 * @param string $id			Required. Meta Box ID
 * @param string $title 		Required. Title of the meta box
 * @param string $screens 		Required. CPT slug
 * @param string $context 		The context within the screen where the box should display
 * @param string $priority 		The priority within the context where the box should show
 * @param array $callback_args	Data that should be set as the $args property of the box array
 */
$init = new My_Class_Post_Meta_box(
			'my-post-custom-fields',
			__( 'Custom Fields title', 'text-domain' ),
			'post',
			'advanced',
			'default',
			null
		);
$init->init();
```

Ver documentación sobre **Meta Boxes** https://developer.wordpress.org/reference/functions/add_meta_box/

## Taxonomies
Para crear nuevas taxonomías necesitamos crear una clase que extienda de `WASP_Taxonomy`, y declaramos el constructor de la siguiente manera:
```php
class My_Class_Taxonomy extends WASP_Taxonomy
{
	function __construct()
	{

		$this->post_type 	= 'post';
		$this->taxonomy 	= 'my-taxonomy';

		$this->labels = array( ... );

		$this->args = array(
			'labels'		=> $this->labels,
			...
		);
	}
}

$init = new My_Class_Taxonomy;
$init->init();
```
Ver documentación sobre **Custom Taxonomies** https://developer.wordpress.org/reference/functions/register_taxonomy/

## Enqueue Scripts

La clase `WASP_Enqueue` nos permite agregar scripts y plugins js a nuestro sitio. El método `scripts()` es un ejemplo de cómo hacerlo.
```php
$enqueue = new WASP_Enqueue();
$enqueue->scripts( true );
```

## NPM & Webpack
En caso de que sea necesario usar `npm` y `webpack` ya tenemos configurado un `package.json` con lo mínimo para un entorno tanto de desarrollo como de producción.
```bash
npm install
npm run build
# or
npm run watch
```
Se puede ampliar en este tema en el siguiente enlace https://rogertm.dev/entorno-desarrollo-npm-webpack/

## Repo template
Puedes generar tu propio repositorio a partir de este y usarlo como un _template_, solo debes pulsar el botón **Use this template**.

Luego es recomendable cambiar algunas cosas para una mayor facilidad a la hora de trabajar:

1. **Prefijo de clases**: Buscar `WASP_` y reemplazar por `Your_Class_Prefix`.
2. **Prefijo de funciones**: Buscar `wasp_` y reemplazar por `your_function_prefix`.
3. **Text domain**: Buscar `'wasp'` (entre comillas simples) y reemplazar por `'your-text-domain'`.
4. **Slug**: Buscar `wasp-` y reemplazar por `your-slug-`.
5. **Comentarios y documentación**: Buscar `WASP` y reemplazar por `Your project name`.
6. **Archivos**: Buscar todos los archivos dentro del directorio `/classes` y cambiar el `slug` de cada uno por el que se ha especificado en el paso **4**. Ej: `class.wasp-admin-page.php` por `class.your-slug-admin-page.php`. Hacer lo mismo con el archivo `wasp.php`en la raíz del plugin.
7. Editar la cabecera del plugin según sea necesario.

Es importante seguir estos pasos en el mismo orden que se muestran.
