<?php

$class_dumper = $class_application::getDumperClass();
$class_entity = $class_application::getEntityClass();
$class_tokens_stream = $class_application::getTokensStreamClass( NAMESPACE_CID );

$source_classes_names = '/includes/test.php';

$format = $class_entity::getDefaultType( NULL, ENTITY_FORMAT )->{PROPERTY_VALUE};
$source = '<?php echo "hello world";';

$properties = array(
	PROPERTY_MODE_ACCESS => FILE_ACCESS_MODE_WRITE_ONLY,
	PROPERTY_SIGNAL => $source,
	PROPERTY_URI_REQUEST => $source_classes_names
);

$bytes = $class_tokens_stream::writeToStream( $properties );

fprint( array( '[number of bytes written]', $bytes ), $verbose_mode );

/**
*************
* Changes log
*
*************
* 2011 10 04
*************
* 
* Implement unit test to write stream
*
* (branch 0.1 :: revision :: 676)
* (branch 0.2 :: revision :: 380)
*
*/