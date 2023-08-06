<?php

  session_start();
  session_unset();
  session_destroy();

  header('Location: ../?page=1&logout');

?>