<?php

/**
* Toolbox class
*
* Class providing various tools
* @package  sefi
*/
class Toolbox
{
	static protected $classes = NULL;
	static protected $_properties = NULL;

	/**
	* Call magically a static method
	*
	* @param	string	$name
	* @param	array	$arguments
	* @return 	mixed	return value
	*/	    
	public static function __callStatic($name, $arguments)
	{
		$classes = array(
			ENTITY_AFFORDANCE => CLASS_AFFORDANCE,
			ENTITY_ALPHA => AGENT_ALPHA,
			ENTITY_ARC => CLASS_ARC,
			ENTITY_ADMINISTRATOR => CLASS_ADMINISTRATOR,
			ENTITY_API => CLASS_API,
			ENTITY_APPLICATION => CLASS_APPLICATION,
			ENTITY_AUTHOR => CLASS_AUTHOR,
			ENTITY_DATABASE => CLASS_DATABASE,
			ENTITY_DATA_FETCHER => CLASS_DATA_FETCHER,
			ENTITY_DB => CLASS_DB,
			ENTITY_DEPLOYER => CLASS_DEPLOYER,
			ENTITY_DIAPORAMA => CLASS_DIAPORAMA,
			ENTITY_DOM => CLASS_DOM,
			ENTITY_DUMPER => CLASS_DUMPER,
			ENTITY_CONTENT => CLASS_CONTENT,
			ENTITY_CONTENT_MANAGER => CLASS_CONTENT_MANAGER,
			ENTITY_CONTEXT => AGENT_CONTEXT,
			ENTITY_CONTROLLER => CLASS_CONTROLLER,
			ENTITY_CRAFTSMAN => CLASS_CRAFTSMAN,
			ENTITY_DOM_ATTRIBUTE => CLASS_DOM_ATTRIBUTE,
			ENTITY_DOM_DOCUMENT => CLASS_DOM_DOCUMENT,
			ENTITY_DOM_ELEMENT => CLASS_DOM_ELEMENT,
			ENTITY_DOM_NODE => CLASS_DOM_NODE,
			ENTITY_DOM_NODE_LIST => CLASS_DOM_NODE_LIST,
			ENTITY_DOM_TEXT => CLASS_DOM_TEXT,
			ENTITY_EDGE => CLASS_EDGE,
			ENTITY_ELEMENT => CLASS_ELEMENT,
			ENTITY_ELEMENT_HTML => CLASS_ELEMENT_HTML,
			ENTITY_ENTITY => AGENT_ENTITY,
			ENTITY_ENTITY_TYPE => CLASS_ENTITY_TYPE,
			ENTITY_ENVIRONMENT => AGENT_ENVIRONMENT,
			ENTITY_EVENT => CLASS_EVENT,
			ENTITY_EVENT_MANAGER => CLASS_EVENT_MANAGER,
			ENTITY_EXCEPTION => CLASS_EXCEPTION,
			ENTITY_EXCEPTION_HANDLER => CLASS_EXCEPTION_HANDLER,
			ENTITY_EXECUTOR => CLASS_EXECUTOR,
			ENTITY_FEED_READER => CLASS_FEED_READER,
			ENTITY_FEEDBACK => CLASS_FEEDBACK,
			ENTITY_FIELD_HANDLER => CLASS_FIELD_HANDLER,
			ENTITY_FILE_MANAGER => CLASS_FILE_MANAGER,
			ENTITY_FLAG => CLASS_FLAG,
			ENTITY_FLAG_MANAGER => CLASS_FLAG_MANAGER,
			ENTITY_FOLDER => CLASS_FOLDER,
			ENTITY_FORM => CLASS_FORM,
			ENTITY_FORM_MANAGER => CLASS_FORM_MANAGER,
			ENTITY_HEADER => CLASS_HEADER,
			ENTITY_HTML_INPUT => CLASS_HTML_INPUT,
			ENTITY_HTML_SELECT => CLASS_HTML_SELECT,
			ENTITY_HTML_TAG => CLASS_HTML_TAG,
			ENTITY_HTML_TEXTAREA => CLASS_HTML_TEXTAREA,
			ENTITY_I18N => CLASS_I18N,
			ENTITY_INSIGHT => CLASS_INSIGHT,
			ENTITY_INSIGHT_NODE => CLASS_INSIGHT_NODE,
			ENTITY_INTERCEPTOR => CLASS_INTERCEPTOR,
			ENTITY_JQUERY4PHP => CLASS_JQUERY4PHP,
			ENTITY_LAYOUT_MANAGER => CLASS_LAYOUT_MANAGER,
			ENTITY_LOCK => CLASS_LOCK,
			ENTITY_LOCKSMITH => CLASS_LOCKSMITH,
			ENTITY_LSQL => CLASS_LSQL,
			ENTITY_MEDIA_MANAGER => CLASS_MEDIA_MANAGER,
			ENTITY_MEMBER => CLASS_MEMBER,
			ENTITY_MEMENTO => CLASS_MEMENTO,
			ENTITY_MEMORY_CACHE => CLASS_MEMORY_CACHE,
			ENTITY_MESSAGE => CLASS_MESSAGE,
			ENTITY_MESSENGER => CLASS_MESSENGER,
			ENTITY_MYSQLI => CLASS_MYSQLI,
			ENTITY_OBJECT_BUILDER => CLASS_OBJECT_BUILDER,
			ENTITY_OPTIMIZER => CLASS_OPTIMIZER,
			ENTITY_PAPER_MAKER => CLASS_PAPER_MAKER,
			ENTITY_PARSER => CLASS_PARSER,
			ENTITY_PDO => CLASS_PDO,
			ENTITY_PHOTO => CLASS_PHOTO,
			ENTITY_PHOTOGRAPH => CLASS_PHOTOGRAPH,
			ENTITY_PLACEHOLDER => CLASS_PLACEHOLDER,
			ENTITY_PROCESSOR => CLASS_PROCESSOR,
			ENTITY_PROVER => CLASS_PROVER,
			ENTITY_QUERY => CLASS_QUERY,
			ENTITY_ROUTE => CLASS_ROUTE,
			ENTITY_ROUTER => CLASS_ROUTER,
			ENTITY_SERIALIZER => CLASS_SERIALIZER,
			ENTITY_SERVICE_MANAGER => CLASS_SERVICE_MANAGER,
			ENTITY_STANDARD_CLASS => CLASS_STANDARD_CLASS,
			ENTITY_STYLESHEET => CLASS_STYLESHEET,
			ENTITY_SMARTY_SEFI => CLASS_SMARTY_SEFI,
			ENTITY_SOURCE => CLASS_SOURCE,
			ENTITY_STORE => CLASS_STORE,
			ENTITY_STORE_ITEM => CLASS_STORE_ITEM,
			ENTITY_TAG_FIELDSET => CLASS_TAG_FIELDSET,
			ENTITY_TAG_DIV => CLASS_TAG_DIV,
			ENTITY_TAG_FORM => CLASS_TAG_FORM,
			ENTITY_TAG_HTML => CLASS_TAG_HTML,
			ENTITY_TAG_INPUT => CLASS_TAG_INPUT,
			ENTITY_TAG_P => CLASS_TAG_P,
			ENTITY_TAG_SELECT => CLASS_TAG_SELECT,
			ENTITY_TAG_SPAN => CLASS_TAG_SPAN,
			ENTITY_TAG_TEXTAREA => CLASS_TAG_TEXTAREA,			
			ENTITY_TAG_HTML => CLASS_TAG_HTML,
			ENTITY_TEMPLATE_ENGINE => CLASS_TEMPLATE_ENGINE,
			ENTITY_TEST_CASE => CLASS_TEST_CASE,
			ENTITY_TWITTEROAUTH => CLASS_TWITTEROAUTH,
			ENTITY_TOKEN => CLASS_TOKEN,
			ENTITY_TOOLBOX => CLASS_TOOLBOX,
			ENTITY_TRANSFER => CLASS_TRANSFER,
			ENTITY_USER => CLASS_USER,
			ENTITY_USER_HANDLER => CLASS_USER_HANDLER,
			ENTITY_USER_INTERFACE => CLASS_USER_INTERFACE,
			ENTITY_VALIDATOR => CLASS_VALIDATOR,
			ENTITY_VIEW_BUILDER => CLASS_VIEW_BUILDER,
			ENTITY_WEAVER => CLASS_WEAVER,
			ENTITY_YAML => CLASS_YAML,
			ENTITY_ZIP_ARCHIVE => CLASS_ZIP_ARCHIVE
		);

		if ( is_null( self::$classes ) )

			self::$classes = &$classes;

		$class_dumper = self::$classes[ENTITY_DUMPER];

		$class_parser = self::$classes[ENTITY_PARSER];

		$class = CLASS_ENTITY;

		$namespace = '';

		$pattern =
			REGEXP_OPEN.
			ACTION_GET.
			'(\S+)(?:'.
			ucfirst(ENTITY_CLASS).'|'.
			ucfirst(ENTITY_AGENT).
			')'.
			REGEXP_CLOSE
		;

		$match = preg_match($pattern, $name, $submatches);

		if ( $match && ! empty( $submatches[1] ) )
		{
			$method_name = $submatches[1];

			$class_name = strtolower(
				$class_parser::translate_entity(
					$method_name,
					ENTITY_NAME_CLASS,
					ENTITY_NAME_METHOD
				)
			);

			if ( isset( self::$classes[$class_name] ) )
			{
				if ( isset( $arguments[0] ) )
				{
					$constant = reverse_constant(
						$arguments[0],
						strtoupper( PREFIX_NAMESPACE )
					);

					if ( ! is_null( $constant ) )
					
						$namespace = $arguments[0].'\\';
				}

				$class = $namespace.self::$classes[$class_name];
			}
			else

				throw new \Exception(
					EXCEPTION_INVALID_ARGUMENT.': '.
					sprintf( EXCEPTION_DEVELOPMENT_MISSING_CLASS, $class_name )
				);
		}
		else
		
			throw new Exception( EXCEPTION_FURTHER_IMPLEMENTATION_REQUIRED );

		return $class;
	}

	/**
	* Beautify a source
	*
	* @param	string		$source 			a source
	* @param	string		$source_type 		type of source
	* @param	boolean		$clean_source 		clean up flag
	* @param	boolean		$declare_doctype	doctype flag
	* @param	boolean		$plant_tree			XML root flag
	* @param	array		$config 			configuration
	* @return  	string		a beautified source
	*/	
	public static function beautifySource(
		$source = null,
		$source_type = NULL,
		$clean_source = VALIDATE_TIDY_SOURCE,
		$declare_doctype = VALIDATE_DOCTYPE_DECLARATION,
		$plant_tree = VALIDATE_TREE_PLANTING,
		$config = null
	)
	{
		global $class_application;

		$class_dumper = $class_application::getDumperClass();

		$class_source = $class_application::getSourceClass();

		$source_type_html = $class_source::getDefaultType();
			
		if ( is_null( $source_type ) )
		
			$source_type = $source_type_html;

		switch ($source_type_html)
		{
			case $source_type_html:

				$_config = array(
					TIDY_OPTION_INDENT => VALIDATE_TIDY_AUTO_INDENT,
					TIDY_OPTION_MARKUP => VALIDATE_TIDY_MARKUP,			
					TIDY_OPTION_OUTPUT_XHTML => VALIDATE_TIDY_OUTPUT_HTML,
					TIDY_OPTION_WRAP => VALIDATE_TIDY_WRAP,
					TIDY_OPTION_BODY_ONLY => TIDY_FLAG_BODY_ONLY
				);
				
				if (
					isset($config) &&
					is_array($config) &&
					count($config) != 0
				)
		
					// loop on option configuration argument
					foreach ($config as $option => $flag)
		
						$_config[$option] = $flag;
		
				if (
					$clean_source &&
					function_exists(FUNCTION_TIDY_PARSE_STRING )
				)
				{
					$tidy = tidy_parse_string( $source, $_config, 'UTF8' );
		
					$source = $tidy->value;
				}
		
				if ( $declare_doctype )
		
					$source = DOCTYPE_XHTML_TRANSITIONAL.$source;
		
				if ( $plant_tree )
		
					$source = DOCUMENT_ROOT_XML.$source;			

					break;
		}

		// add space between closing bracket of single tags 
		$source = preg_replace( '/\s*\/>/', ' />', $source );

		// remove empty title tags
		$source = preg_replace( '/<title><\/title>/', '', $source );

		// restore insecable space
		$source = str_replace( '&amp;nbsp;', '&', $source );
		
		return $source;        	
	}
	/*
    * Alias to the buildSpace method
    *
    * @param	integer	$dimension	representing a dimension
    * @return	array	a space
	*/
	public static function build_space($substrate)
	{
		return self::buildSpace($substrate);
	}

	/*
    * Build a space
    *
    * @param	integer	$dimension	representing a dimension
    * @return	array	a space
	*/
	public static function buildSpace($substrate)
	{
		// declare an empty array
		$space = array();

		// set the coordinate index
		$coordinate_index = 0;

		// check if the substrate is an integer
		if (is_int($substrate))

			while ($coordinate_index < $substrate)
			{
				$space[] = CHARACTER_EMPTY_STRING;
				$coordinate_index++;
			}

		// check if the substrate is an array
		else if (is_array($substrate))

			// loop on the substrate
			while (list($index, $value) = each($substrate))
			{
				if (
					empty($value) ||
					is_object($value) ||
					is_array($value)
				)

					$space[$index] = CHARACTER_EMPTY_STRING;

				$coordinate_index++;
			}
	
		// check if the substrate is a string
		else if (
			is_object($substrate) &&
			isset($substrate->{PROPERTY_LENGTH}) &&
			isset($substrate->{PROPERTY_PATTERN})
		)

			// loop on substrate
			for ($k = 1; $k <= $substrate->{PROPERTY_LENGTH}; $k++)
	
				$space[$k] = $substrate->{PROPERTY_PATTERN};

		return $space;
	}

	/*
    * Check the keys of an array
    *
    * @param	array	$array 	values
    * @return	mixed
	*/
	public static function check_array_keys($array)
	{
		$contains_associative_key = false;

		// check the array argument
		if (
			is_array($array) &&
			count($array) != 0
		)
		{

			while (list($key, $value) = each($array))

				if (is_string($key))

					$contains_associative_key = true;
		}
		else 

			return;

		return $contains_associative_key;
	}

	/*
    * Check email
    *
    * @param	string	$email	containing a string to be checked
    * @return	boolean	indicating if the provided argument is a valid email
	*/		
	public static function check_email($email)
	{
		$email = strtolower(trim($email));

		$qtext = '[^\\x0d\\x22\\x5c\\x80-\\xff]';

		$dtext = '[^\\x0d\\x5b-\\x5d\\x80-\\xff]';

		$atom = '[^\\x00-\\x20\\x22\\x28\\x29\\x2c\\x2e\\x3a-\\x3c'.
				'\\x3e\\x40\\x5b-\\x5d\\x7f-\\xff]+';

		$quoted_pair = '\\x5c[\\x00-\\x7f]';

		$domain_literal = "\\x5b($dtext|$quoted_pair)*\\x5d";

		$quoted_string = "\\x22($qtext|$quoted_pair)*\\x22";

		$domain_ref = $atom;

		$sub_domain = "($domain_ref|$domain_literal)";

		$word = "($atom|$quoted_string)";

		$domain = "$sub_domain(\\x2e$sub_domain)*";

		$local_part = "$word(\\x2e$word)*";

		$addr_spec = "$local_part\\x40$domain";

		return preg_match("!^$addr_spec$!", $email) ? $email : false;
	}

	/*
    * Alias to escape_string method
    *
    * @param	string	$string containing special characters
    * @return	string	with special characters escaped
	*/	
	public static function escape($string)
	{
		return self::escape_string($string);
	}

	/*
    * Escape string
    *
    * @param	string	$string containing special characters
    * @return	string	with special characters escaped
	*/	
	public static function escape_string($string)
	{
		$class_db = CLASS_DB;

		// return escaped string
		return $class_db::sanitize($string);
	}

	/*
    * Alias to escape_string method
    *
    * @param	string	$string containing special characters
    * @return	string	with special characters escaped
	*/	
	public static function escapeString($string)
	{
		return self::escape_string($string);
	}

	/*
    * Alias to escape_string method
    *
    * @param	string	$string containing special characters
    * @return	string	with special characters escaped
	*/	
	public static function escapeSpecialCharacters($string)
	{
		return self::escape_string($string);
	}

	/*
    * Format a date
    *
    * @param	string		$string 	containing a date
    * @param	boolean		$sql_form	indicating if the sql format is to be used
    * @return	string	containing a date
	*/	
	public static function format_date($date, $sql_format = true)
	{
		if ($sql_format)
		{

			// split the birthday into its three components
			list($year, $month, $day) = explode(SEPARATOR_DASH, $date);
	
			// set the year prefix
			$year_prefix = ($year < 70) ? '20' : '19';
	
			// set the birthday to be recorded in database
			$result = implode(SEPARATOR_DASH, array($year_prefix.$year, $month, $day));
		}
		else
		{

			// split the birthday into its three components
			list($year, $month, $day) = explode(SEPARATOR_DASH, $date);
	
			// crop the two first characters of the year
			$year = substr($year, 2);

			// set the birthday to be recorded in database
			$result = implode(SEPARATOR_DASH, array($year, $month, $day));			
		}

		// return the result
		return $result;
	}

	/*
    * Generate a password
    *
    * @return	string	containing a password
	*/	
	public static function generate_password()
	{
		// set random numbers ranges
		$min = rand(0, 10);
		$max = rand(1000000, 1000001);

		// set a random number
		$random_number = rand($min, $max);

		// hash the random number
		$password = substr(sha1(substr(md5($random_number), 0, 10)), 0, 12);		

		// return a passwod
		return $password;
	}

	/**
	* Generate a unique entity id
	*
	* @param	mixed	$entity			representation of an entity
	* @param	string	$entity_type	type of an entity
	* @return	mixed	unique id
	*/
	public static function generateUniqueEntityId($entity, $entity_type)
	{
		$id = self::generate_password();

		switch ($entity_type)
		{
			case ENTITY_INSIGHT:
		
				if (
					is_array($entity) && count($entity) > 0 &&
					isset($entity[PROPERTY_THREAD]) &&
					isset($entity[PROPERTY_OWNER])
				)
				
					$id = sha1($entity[PROPERTY_THREAD].'-'.$entity[PROPERTY_OWNER]);
				else

					throw new Exception(EXCEPTION_CONSISTENCY_DATA_ACCESS_ENTITY_DESCRIPTION_INCOMPLETE);

					break;

			case ENTITY_INSIGHT_NODE:

				if (
					is_object($entity) && 
					isset($entity->{PROPERTY_ID}) &&
					isset($entity->{PROPERTY_OWNER}) &&
					isset($entity->{PROPERTY_THREAD})
				)

					$id = sha1($entity->{PROPERTY_THREAD}.'-'.$entity->{PROPERTY_OWNER}.'-'.$entity->{PROPERTY_ID});

				else if (count((array)$entity) == 0)

					throw new Exception(EXCEPTION_CONSISTENCY_DATA_ACCESS_ENTITY_DESCRIPTION_INCOMPLETE);

					break;			
		}
		
		return $id;
	}

	/**
	* Get feedback links
	*
	* @param	string	$form_identifier	form identifier
	* @return	nothing
	*/
	public static function getFeedbackLinks( $form_identifier )
	{
		$class_dumper = self::getDumperClass();
		
		$links = array();

		$form_description = self::getFormDescription( $form_identifier );

		if (
			FALSE !==
			(
				$key_exists = self::keys_exists(
					$form_description,
					array(
						PROPERTY_FEEDBACK,
						PROPERTY_SUCCESS,
						PROPERTY_LINKS
					),
					TRUE
				)
			)	
		)
		
			$links[PROPERTY_SUCCESS] = $key_exists;

		if (
			FALSE !==
			(
				$key_exists = self::keys_exists(
					$form_description,
					array(
						PROPERTY_FEEDBACK,
						PROPERTY_FAILURE,
						PROPERTY_LINKS
					),
					TRUE
				)
			)	
		)
		
			$links[PROPERTY_FAILURE] = $key_exists;

		$class_dumper::log(
			__METHOD__,
			array(
				'count:',
				count( $links),
				'links: ',
				$links
			),
			TRUE
		);
		return $links;
	}

	/**
	* Get a feedback message
	*
	* @param	mixed	$stack 				$stack
	* @param	string	$form_identifier	form identifier
	* @param	mixed	&$destination		destination variable
	* @return	nothing
	*/
	public static function getFeedbackMessage(
		$stack,
		$form_identifier,
		&$destination
	)
	{
		global $verbose_mode;

		$class_dumper = self::getDumperClass();

		$form_description = self::getFormDescription( $form_identifier );

		if ( $form_description )
		{
			if (
				FALSE !==
				(
					$container = self::keys_exists(
						$form_description,
						$stack
					)
				)
			)
			{
				if ( is_array( $container ) )

					list( , $language_item ) = $container;
				else 
		
					$language_item = $container;

				$constant_message =
					strtoupper( 
						substr( PREFIX_FORM, 0, -1).
							'_'.
								PREFIX_FEEDBACK.
									str_replace(
										'.',
										'_',
										$form_identifier
									).
										'_'.
											$language_item
					)
				;
		
				if ( defined( $constant_message ) )
					
					$destination = constant(
						$constant_message
					);
			}
		}
	}

	/**
	* Get a feedback message
	*
	* @param	mixed	$stacks 			stacks
	* @param	string	$form_identifier	form identifier
	* @return	nothing
	*/
	public static function getFeedbackMessages(
		&$stacks,
		$form_identifier
	)
	{
		if ( is_array( $stacks ) && count( $stacks ) )
	
			while ( list( , $properties ) = each( $stacks ) )
	
				self::getFeedbackMessage(
					$properties[PROPERTY_STACK],
					$form_identifier,
					$properties[PROPERTY_DESTINATION]
				);
	}

	/**
	* Get a form description
	*
	* @param	string	$form_identifier	form identifier
	* @return	mixed	form description
	*/
	public static function getFormDescription( $form_identifier )
	{
		global $verbose_mode;

		$class_dumper = self::getDumperClass();

		$class_data_fetcher = self::getDataFetcherClass();

		$class_yaml = self::getYamlClass();

		$form_description = NULL;

		if ( is_null( self::$_properties ) )
		
			self::$_properties = new stdClass();

		if ( ! isset( self::$_properties->{PROPERTY_FORM_CONFIGURATION} ) )

			self::$_properties->{PROPERTY_FORM_CONFIGURATION} = array();

		if (
			! isset(
				self::$_properties->{PROPERTY_FORM_CONFIGURATION}[$form_identifier]
			)
		)

			$form_configuration = 

			self::$_properties->{PROPERTY_FORM_CONFIGURATION}[$form_identifier] =
				$class_data_fetcher::fetchFormConfiguration(
					$form_identifier
				)
			;
		else

			$form_configuration =
				self::$_properties
					->{PROPERTY_FORM_CONFIGURATION}[$form_identifier]
			;

		if ( isset( $form_configuration->{PROPERTY_CONFIGURATION} ) )
		{
			if ( ! isset( self::$_properties->{PROPERTY_FORM_DESCRIPTION} ) )

				self::$_properties->{PROPERTY_FORM_DESCRIPTION} = array();
			
			if (
				! isset(
					self::$_properties->{PROPERTY_FORM_DESCRIPTION}[$form_identifier]						
				)
			)
			{
				$form_description =
				
				self::$_properties->{PROPERTY_FORM_DESCRIPTION}[$form_identifier] = 
	
				$class_yaml::load(
					$form_configuration->{PROPERTY_CONFIGURATION}
				);
			}
			else

				$form_description = 

					self::$_properties
						->{PROPERTY_FORM_DESCRIPTION}
							[$form_identifier]
				;
		}

		return $form_description;
	}

	/**
	* Filter a form identifier
	*
	* @param	string 	$material	material
	* @return	mixed	filtered identifier
	*/
	public static function getLanguageItemPrefix( $material )
	{
		global $class_application, $verbose_mode;

		$prefix = NULL;

		if ( is_string( $material ) && ! empty( $material ) )

			$form_identifier = $material;
		else

			throw new Exception( EXCEPTION_INVALID_ARGUMENT );

		$administration =
			strpos(
				$form_identifier,
				PREFIX_ADMINISTRATION
			) !== FALSE
		;

		$edition = strpos( $form_identifier, PREFIX_EDITION ) !== FALSE;

		// set the i18n prefixes
		$i18n_prefixes = array( PROPERTY_NAMESPACE => LANGUAGE_PREFIX_FORM );

		$identifier =
			$administration ?
			substr( $form_identifier, strlen( PREFIX_ADMINISTRATION ) ) :
			$form_identifier
		;

		// set the form identifier
		$identifier =
			$edition ?
			substr( $identifier, strlen( PREFIX_EDITION ) ) :
			$identifier
		;

		// set the form identifier
		$prefix_affordance =
			$class_application::translate_entity(
				$identifier,
				ENTITY_CSS_CLASS
			)."_"
		;

		$prefix = $i18n_prefixes
			[PROPERTY_NAMESPACE].
				PREFIX_LABEL.
					$prefix_affordance
		;

		return $prefix;
	}

	/**
	* Generate a unique id
	*
	* @param 	string	$attribute	attribute 
	* @param	string	$id			id
	* @param	string	$form_id	form id
	* @param	string	$name 		name
	* @param	string 	$option		option,
	* @param	string	$target		target
	* @param	string	$type		type
	* @return	string	id
	*/
	public static function generateUniqueId(
		$attribute,
		$id,
		$form_id,
		$name,
		$option,
		$target,
		$type
	)
	{
		global $class_application, $verbose_mode;

		$section_element_based = 
				$type !=
					HTML_ELEMENT_INPUT &&											
				(
					$type !=
						HTML_ELEMENT_LABEL ||
					$attribute == HTML_ATTRIBUTE_ID
				) 
			?
				$type.'_'
			:
				''
		;

		// set the attribute value
		$attribute_value =
			$form_id.'_'.
				$section_element_based.
			(
				! empty( $option )
			?
				$option . '_'
			:
				''
			).
			HTML_ATTRIBUTE_ID . '_' . $name
		;

		if ( empty( $id  ) )

			// hash the attribute value
			$value = $class_application::hash( $target.$attribute_value );
		else

			// set a specific id
			$value = rtrim( $type, SUFFIX_MANDATORY ) . '_' . $id;

		return $value;
	}

	/*
    * Hash a string
    *
    * @param	string	$string	string
    * @return	string
	*/	
	public static function hash($string)
	{
		return "_".substr(md5($string), 0, 5);
	}

	/**
	* Check if an entity has an access key 
	*
	* @param	mixed		&$entity		entity
	* @param	string		$key			key
	* @param	string		$symbol_name	name of a symbol
	* @return  	boolean		indicator
	*/	
	public static function key_exists(
		&$entity,
		$key,
		$symbol_name = NULL
	)
	{
		global $symbols, $verbose_mode;

		$class_dumper = self::getDumperClass();

		$update_symbols = ! is_null( $symbol_name );

		$key_exists = FALSE;

		if ( ! is_null( $entity ) )
		{
			if ( is_array( $entity ) )
			{
				if ( $update_symbols && ! isset( $symbols[$symbol_name] ) )

					$symbols[$symbol_name] = array(
						PROPERTY_ISA => 'array'
					);
  
				if ( isset( $entity[$key] ) )

					$key_exists = array( $entity[$key] );

				else if ( $update_symbols )
				{
					if ( ! isset( $symbols[$symbol_name][PROPERTY_KEYS] ) )

						$symbols[$symbol_name][PROPERTY_KEYS] = array();

					$symbols[$symbol_name][PROPERTY_KEYS][$key] = 
						PROPERTY_UNDECLARED
					;
				}
			}
			else if (
				is_object( $entity ) &&
				(
					// check if the key contains a property
					is_array( $key ) ||

					// check if the key is a member variable
					is_string( $key ) &&
					isset( $entity->$key )
				)
			)
			{
				if ( $update_symbols && ! isset( $symbols[$symbol_name] ) )

					$symbols[$symbol_name] = array(
						PROPERTY_ISA => get_class( $entity )
					);
 
				if ( is_array( $key ) )
				{
					list( $property, $callback ) = each( $key );
					
					switch ( $property )
					{
						case PROPERTY_METHOD:

							if ( is_array( $callback ) )
							{
								list( $method, $arguments ) = $callback;

								if (
									in_array(
										$method,
										get_class_methods(
											get_class( $entity )
										)
									)
								)
								{
									$key_exists = array(
										call_user_func_array(
											array(
												$entity,
												$method
											),
											$arguments
										)
									);
								}
								else

									throw new Exception(
										sprintf(
											EXCEPTION_MISSING_ENTITY,
											ENTITY_METHOD
										)
									);
							}
							else

								throw new Exception(
									EXCEPTION_INVALID_CALLBACK
								);
	
									break;
					}
				}
				else
				{
					if ( isset( $entity->$key ) )
	
						$key_exists = array( $entity->$key );
	
					else if ( $update_symbols )
					{
						if ( ! isset( $symbols[$symbol_name][PROPERTY_KEYS] ) )
	
							$symbols[$symbol_name][PROPERTY_KEYS] = array();
	
						$symbols[$symbol_name][PROPERTY_KEYS][$key] = 
							PROPERTY_UNDECLARED
						;
					}
				}
			}
		}
		else if ( $update_symbols )

			$symbols[$symbol_name] = array(
				PROPERTY_IS_NULL => TRUE
			);

		return $key_exists;
	}

	/**
	* Check if an entity has an access key 
	*
	* @param	mixed		$entity				entity
	* @param	string		$keys				keys
	* @param	string		$extract_value		extract value
	* @param	string		$symbol_name		symbol name
	* @param	string		$traversing_type	traversing type
	* @return  	boolean		indicator
	*/	
	public static function keys_exists(
		&$entity,
		$keys,
		$extract_value = FALSE,
		$symbol_name = NULL,
		$traversing_type = NULL
	)
	{
		global $class_application, $verbose_mode;

		$class_dumper = self::getDumperClass();

		$default_traversing_type = TRAVERSING_TYPE_RECURSIVELY;

		$entities = array( &$entity );

		$keys_exists = FALSE;

		if ( is_null( $traversing_type ) )

			$traversing_type = $default_traversing_type;

		if ( is_array( $keys ) && count( $keys ) )
		{
			switch ( $traversing_type )
			{
				// recursive traversing type 
				case $default_traversing_type:

					while (
						( list( $index, $key ) = each( $keys ) ) &&
						(
							$key_exists =
								self::key_exists(
									$entity,
									$key,
									$symbol_name
								)
						) &&
						is_array( $key_exists )
					)
					{
						list( $_index, $_key ) = each( $key_exists );

						$entities[$index + 1] = &$key_exists[$_index];

						$entity = &$_key;
					}

					reset( $keys );

					if ( ! $extract_value )

						$keys_exists = $key_exists;
	
					else if (
						is_array( $key_exists ) &&
						reset( $key_exists )
					)

						list( , $keys_exists ) = each( $key_exists );

						break;
			}

			if (
				$keys_exists !== FALSE &&
				is_array( $keys_exists ) &&
				! $extract_value
			)

				$keys_exists[] = $entities;
		}

		return $keys_exists;
	}

	/*
    * Replace special characters in a string
    *
    * @param	string	$haystack containing special characters
    * @param	string	$needle containing a special character
    * @param	string	$replacement containing a replacement
    * @return	string	with special characters replaced
	*/	
	public static function replace_entities($haystack, $needle, $replacement)
	{
		// replace a needle with a replacement
		$result =
			preg_replace(
				REGEXP_OPEN.
					($needle != CHARACTER_UNDERSCORE ? REGEXP_ESCAPE : '').$needle.
				REGEXP_CLOSE,
				$replacement,
				$haystack
			);		
	
		// return a result
		return $result;
	}

	/**
	 * Rewrite a string by leaving only alpha-numerical characters
	 * and replacing anything else by dashes
	 *
	 * @param 	string 	$string	string
	 * @return 	string	rewritten string
	 */
	public static function rewrite($string)
	{
		$string = strtr(
			$string,
			"\xC0\xC1\xC2\xC3\xC4\xC5\xC7\xC8\xC9\xCA\xCB\xCC\xCD\xCE\xCF".
			"\xD0\xD1\xD2\xD3\xD4\xD5\xD6\xD8\xD9\xDA\xDB\xDC\xDD\xDF".
			"\xE0\xE1\xE2\xE3\xE4\xE5\xE7\xE8\xE9\xEA\xEB\xEC\xED\xEE\xEF".
			"\xF0\xF1\xF2\xF3\xF4\xF5\xF6\xF8\xF9\xFA\xFB\xFC\xFD\xFF"
			,
			'AAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaceeeeiiiidnoooooouuuuyy'
		);

		$string = preg_replace('#[^a-z0-9]+#i', '-', strtolower($string));

		$string = trim($string, '-');

		return $string;
	}

    /**
    * Rewrite a file name
    * 
    * @param   string  	$name   name
    * @return  array	file extension and name
    */
    public static function rewriteFileName($name)
    {
		global $class_application, $verbose_mode;

		$class_dumper = $class_application::getDumperClass();
		
        $extension_match = preg_match(
            '/(.*)\.([^\.]*)?$/',
            $name,
            $extension_matches
        );
    
        if ( ! $extension_match || empty( $extension_matches[2] ) )
        {
            $file_extension = EXTENSION_NO_EXTENSION;
    
            $rewritten_file_name = self::rewrite( $name );
        }
        else
        {
            $file_extension = $extension_matches[2];
    
            $rewritten_file_name = self::rewrite(
                    $extension_matches[1]
                ).
                ".".
                $extension_matches[2]
            ;
        }

        return array( $rewritten_file_name, $file_extension );
	}

	/*
    * Shorten a sentence
    *
    * @param	string	$sentence		sentence
    * @param	boolean	$crop 			cropping flag
    * @param	string 	$max_characters	maximmum characters count 
    * @param	string	$ellipsis		ellipsis symbol
    * @param	boolean	$pad			padding flag
    * @return	string	sentence shortened
	*/
	public static function shorten_sentence(
		$sentence,
		$crop = TRUE, 
		$max_characters = 140,
		$ellipsis = ' [...]',
		$pad = TRUE
	)
	{
		$_max_characters = $max_characters;

		if ( ! is_null( $ellipsis ) )

			$_max_characters = $max_characters - strlen( $ellipsis );

		$sentence_shortened = '';

		$_words = array();

		$words = explode( ' ', $sentence );

		array_map(
			function( $value )
			{
				str_replace( "\n", '', trim( $value ) );
			},
			$words
		);
		
		$characters_count = 0;

		$remaining_characters = TRUE;

		while (
			( list( , $word ) = each( $words ) ) &&
			( $remaining_characters )
		)
		{
			$characters_count += strlen( $word ) + 1;

			$remaining_characters =
				( $characters_count < $_max_characters ) ||
				! $crop
			;

			if ( $remaining_characters )

				$_words[] = $word;
		}

		$_words[] = $word;

		$sentence_shortened = implode( ' ', $_words ) . $ellipsis;
		
		if ( $pad && ( strlen( $sentence_shortened ) < $max_characters ) )

			$sentence_shortened =
				str_pad( $sentence_shortened, $max_characters, ' ' )
			;

		return $sentence_shortened;
	}

	/*
    * Translate a given entity into another type of entity
    *
    * @param	string	$entity	containing an entity
    * @param	string 	$to 	containing an output entity
    * @param	string	$from	containing an input entity
    * @return	string	containing an entity
	*/	
	public static function translate_entity($entity, $to = ENTITY_URI, $from = ENTITY_AFFORDANCE)
	{
		$result = $entity;

		// check the input and output entities
		switch ($from)
		{
			case ENTITY_ACTION:
			case ENTITY_AFFORDANCE:
			case ENTITY_OPERATION:

				switch ($to)
				{
					case ENTITY_NAME_METHOD:

						$words = explode('.', $entity);
						
						while (list($index, $word) = each($words))
						
							$words[$index] = $index ? ucfirst($word) : $word;
							
						$result = implode($words);
	
							break;

					case ENTITY_CONSTANT:
					case ENTITY_CSS_CLASS:
					case ENTITY_PHP_VARIABLE:
					case ENTITY_SMARTY_VARIABLE:
			
						// set the result
						$result = self::replace_entities(
							$entity,
							CHARACTER_FULL_STOP,
							CHARACTER_UNDERSCORE
						);
		
							break;			

					case ENTITY_PATTERN:
	
						// set the result
						$result = self::replace_entities(
							$entity,
							CHARACTER_FULL_STOP,
							REGEXP_ESCAPE.CHARACTER_FULL_STOP
						);

							break;

					case ENTITY_URI:
		
						// set the result
						$result = self::replace_entities(
							$entity,
							CHARACTER_FULL_STOP,
							CHARACTER_DASH
						);

							break;
				}

				break;

			case ENTITY_MESSAGE:
			case ENTITY_CONSTANT:

				switch ($to)
				{
					case ENTITY_AFFORDANCE:
	
						// set the result
						$result = self::replace_entities(
							$entity,
							CHARACTER_UNDERSCORE,
							CHARACTER_FULL_STOP
						);

							break;
				}

					break;

			case ENTITY_NAME:
			case ENTITY_TITLE:

				switch ($to)
				{
					case ENTITY_ACTION:

						$result =
							strtolower(
								self::replace_entities(
									$entity,
									's',
									CHARACTER_FULL_STOP
								)
							)
						;

						break;
				}

					break;

			case ENTITY_URI:

				switch ($to)
				{
					case ENTITY_AFFORDANCE:
	
						// set the result
						$result = self::replace_entities(
							$entity,
							CHARACTER_DASH,
							CHARACTER_FULL_STOP
						);

							break;

					case ENTITY_PHP_VARIABLE:
					case ENTITY_SMARTY_VARIABLE:
	
						// set the result
						$result = self::replace_entities(
							$entity,
							CHARACTER_UNDERSCORE,
							REGEXP_ESCAPE.CHARACTER_FULL_STOP
						);

							break;

					case ENTITY_PATTERN:
	
						// set the result
						$result = self::replace_entities(
							$entity,
							CHARACTER_DASH,
							REGEXP_ESCAPE.CHARACTER_FULL_STOP
						);

							break;
				}

			case ENTITY_NAME_METHOD:

				switch ($to)
				{
					case ENTITY_NAME_CLASS:
						
						$offset = 0;

						$offsets =
						$sections = array();

						while ($match = preg_match(
							'/[A-Z]/',
							$entity,
							$submatches,
							PREG_OFFSET_CAPTURE,
							$offset
						))
						{
							if (
								isset($submatches[0]) &&
								is_array($submatches[0]) &&
								isset($submatches[0][0]) &&
								isset($submatches[0][1])
							)

								$offsets[$submatches[0][1]] = $submatches[0][0];

							$offset = $submatches[0][1] + 1;
						}

						list($start) = each($offsets);
					
						while (list($index) = each($offsets))
						{
							$length = $index - $start;

							$sections[count($sections) + 1] = substr($entity, $start, $length);
							
							$start = $index;
						}

						$sections[count($sections) + 1] = substr($entity, $start);

						$result = implode('_', $sections);

					break;
				}	

				break;
		}

		// return a result
		return $result;
	}
}
