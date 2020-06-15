<?php

define("SERVER", "localhost");
define("USER", "root");
define("PASSWORD", "");
define("DB", "tasklist");

$conn = mysqli_connect(SERVER, USER, PASSWORD,DB);

if(!$conn)
    die("Connection failed ". mysqli_connect_error());
