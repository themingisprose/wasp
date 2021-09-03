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
En caso que sea necesario, se puede crear una subpágina de administración extendiendo la clase `WASP_Admin_Sub_Page` y declarando el método `fields()` de dicha clase.

### fields()
Este método debe retornar un array asociativo.
```php
class My_Class_Admin_Sub_Page extends WASP_Admin_Sub_Page
{

	public function fields()
	{
		/**
		 * 'label' string			Nombre del campo
		 * 'description' string		Descripción
		 * 'nonce_attr' string 		Nombre del 'action'
		 * 'nonce_field' string 	Nombre del 'nonce'
		 */
		$fields = array(
			array(
				'label'			=> __( 'Field name', 'text-domain' ),
				'description'	=> __( 'Description', 'text-domain' ),
				'nonce_attr'	=> '_nonce_attribute',
				'nonce_field'	=> '_nonce_field',
			),
			...
		);

		return $fields;

	}
}
```
### __construct() & init()
Se debe inicializar la clase pasando los siguientes parámetros al constructor y luego llamar al método `init()`:
```php
/**
 * @param string $page_title 	Page title
 * @param string $menu_title	Menu title
 * @param string $page_slug		Page slug
 */
$init = new My_Class_Admin_Sub_Page(
			__( 'Page Title', 'text-domain' ),
			__( 'Menu Title', 'text-domain' ),
			'my-submenu-slug'
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
