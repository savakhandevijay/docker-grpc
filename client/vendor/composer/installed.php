<?php return array(
    'root' => array(
        'name' => 'demo/client',
        'pretty_version' => '1.0.0+no-version-set',
        'version' => '1.0.0.0',
        'reference' => null,
        'type' => 'library',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        'demo/client' => array(
            'pretty_version' => '1.0.0+no-version-set',
            'version' => '1.0.0.0',
            'reference' => null,
            'type' => 'library',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'google/protobuf' => array(
            'pretty_version' => 'dev-master',
            'version' => 'dev-master',
            'reference' => '5eff49bc4bcd6adabac3b1be363123e65a36e7f9',
            'type' => 'library',
            'install_path' => __DIR__ . '/../google/protobuf',
            'aliases' => array(
                0 => '9999999-dev',
            ),
            'dev_requirement' => false,
        ),
        'grpc/grpc' => array(
            'pretty_version' => 'dev-master',
            'version' => 'dev-master',
            'reference' => '64fc926ca303e101ebfc6bef73d71e0b6ea4a7fa',
            'type' => 'library',
            'install_path' => __DIR__ . '/../grpc/grpc',
            'aliases' => array(
                0 => '9999999-dev',
            ),
            'dev_requirement' => false,
        ),
    ),
);
