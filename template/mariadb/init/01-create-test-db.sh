#!/usr/bin/env bash

create_test_database() {
    local database_test="${MARIADB_DATABASE}_test"
    local database_test_esc="${MARIADB_DATABASE//_/\\_}\_test"
    local database_user_test="${MARIADB_USER}_test"

    echo "Creating test database: \"${database_test}\""
    echo "Creating test user: \"${database_user_test}\" with same password as user \"${MARIADB_USER}\""

    mariadb -uroot -p${MARIADB_ROOT_PASSWORD} --comments <<EOF
        CREATE DATABASE IF NOT EXISTS \`${database_test}\`;

        CREATE USER IF NOT EXISTS '${database_user_test}'@'%' IDENTIFIED BY '${MARIADB_PASSWORD}';

        GRANT ALL PRIVILEGES ON \`${database_test_esc}\`.* TO '${database_user_test}'@'%';
EOF
}

create_test_database
