$routes->get('{{name}}/list', '{{controller}}::list', '{{auth}}', false);
$routes->get('{{name}}/view/:id', '{{controller}}::view', '{{auth}}', false);
$routes->get('{{name}}/edit/:id', '{{controller}}::edit', '{{auth}}', false);
$routes->post('{{name}}/update', '{{controller}}::update', '{{auth}}', false);
$routes->post('{{name}}/create', '{{controller}}::create', '{{auth}}', false);
$routes->get('{{name}}/new', '{{controller}}::new', '{{auth}}', false);