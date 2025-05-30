<?php

error_reporting(32759);
@ini_set("memory_limit", "1024M");
@ini_set("max_execution_time", 0);
@set_time_limit(0);
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/ajax/db/utl.php";
require_once "../admin/ajax/db/config.php";
if($_GET["action"] == "backup") {
    require_once "../admin/ajax/db/MysqliDb.php";
    require_once "../admin/ajax/db/funciones.php";
    if(!empty($_FILES["file_bk"]["name"])) {
        if($_FILES["file_bk"]["type"] !== "application/zip") {
            $salida = ["estado" => "error", "salida" => "El tipo de archivo es incorrecto,debe ser del tipo .zip"];
            header("Content-Type: application/json");
            echo json_encode($salida, JSON_UNESCAPED_UNICODE);
            exit;
        }
        $namebk = explode("_", $_FILES["file_bk"]["name"]);
        $vsys = intval(str_replace("v6.", "", $namebk[1]));
        if(count($namebk) == 5) {
            if(REVISION_MKWS < $vsys) {
                $salida = ["estado" => "error", "salida" => "El Backup pertenece a la revision <b>" . $vsys . "</b>, El backup debe ser igual o inferior a la revisión<b>" . REVISION_MKWS . "</b>."];
                header("Content-Type: application/json");
                echo json_encode($salida, JSON_UNESCAPED_UNICODE);
                exit;
            }
            $sqlfile = __DIR__ . "/admin/backup/" . str_replace(".zip", ".sql", $_FILES["file_bk"]["name"]);
            unlink($sqlfile);
            unlink(__DIR__ . "/admin/backup/" . $_FILES["file_bk"]["name"]);
            if($_FILES["file_bk"]["name"] && move_uploaded_file($_FILES["file_bk"]["tmp_name"], __DIR__ . "/admin/backup/" . $_FILES["file_bk"]["name"])) {
                exec("unzip -P " . _obfuscated_0D293125300E34080F10181E071B37193F38363F042D11_("passlic") . " -o -j " . __DIR__ . "/admin/backup/" . $_FILES["file_bk"]["name"] . " -d " . __DIR__ . "/admin/backup/");
            }
            if(!file_exists($sqlfile)) {
                $salida = ["estado" => "error", "salida" => "Ha ocurido un error, el backup V6 no fué descomprimido o no contiene un archivo sql correcto <b>" . $sqlfile . "</b>"];
                header("Content-Type: application/json");
                echo json_encode($salida, JSON_UNESCAPED_UNICODE);
                exit;
            }
            exec("sudo mysql --user=\"root\" --port=\"" . PORT_BD . "\" --password=\"" . ROOT_BD_PASSWORD . "\" --host=" . HOST_BD . " " . NAME_BD . " < " . $sqlfile);
            unlink($sqlfile);
            _obfuscated_0D0A022518073826331433062C11270B1F0C120F2C3411_();
            _obfuscated_0D295C021234010A0A25353B1D233315112626151F2932_();
            _obfuscated_0D131A25300306365C05170E3119121A1E0E3919263101_();
            if(function_exists("validalicencia")) {
                _obfuscated_0D5C312B5C0B3E0333303C2D32330C1D1F162E0E033D32_();
            }
            $salida = ["estado" => "exito", "salida" => "El backup Mikrowisp V6 fué restaurado correctamente."];
            header("Content-Type: application/json");
            echo json_encode($salida, JSON_UNESCAPED_UNICODE);
            exit;
        }
        if(count($namebk) == 3) {
            _obfuscated_0D0A022518073826331433062C11270B1F0C120F2C3411_();
            _obfuscated_0D295C021234010A0A25353B1D233315112626151F2932_();
            _obfuscated_0D131A25300306365C05170E3119121A1E0E3919263101_();
            if(function_exists("validalicencia")) {
                _obfuscated_0D5C312B5C0B3E0333303C2D32330C1D1F162E0E033D32_();
            }
            $salida = ["estado" => "Error", "salida" => "No puede puede subir un backup de la V5,utilice un backup correcto."];
            header("Content-Type: application/json");
            echo json_encode($salida, JSON_UNESCAPED_UNICODE);
            exit;
        }
        $salida = ["estado" => "error", "salida" => "El Backup es incorrecto, solo se debe utilizar Backup generados por Mikrowisp.(Sin modificar Nombre,tamaño,etc.)"];
        header("Content-Type: application/json");
        echo json_encode($salida, JSON_UNESCAPED_UNICODE);
        exit;
    }
    _obfuscated_0D0A022518073826331433062C11270B1F0C120F2C3411_();
    _obfuscated_0D295C021234010A0A25353B1D233315112626151F2932_();
    _obfuscated_0D131A25300306365C05170E3119121A1E0E3919263101_();
    if(function_exists("validalicencia")) {
        _obfuscated_0D5C312B5C0B3E0333303C2D32330C1D1F162E0E033D32_();
    }
    $salida = ["estado" => "exito", "salida" => "Se ha saltado el paso de subir backup correctamente"];
    header("Content-Type: application/json");
    echo json_encode($salida, JSON_UNESCAPED_UNICODE);
    exit;
}
if($_GET["action"] == "valid") {
    switch ($_POST["paso"]) {
        case 1:
            $hostname = $_POST["bd_host"];
            $username = $_POST["bd_user"];
            $password = $_POST["bd_pass"];
            $bd = $_POST["bd_name"];
            $port = $_POST["bd_port"];
            try {
                $dbh = new PDO("mysql:host=" . $hostname . ";port=" . $port . ";dbname=" . $bd, $username, $password);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $texto = "<?php\ndefine(\"root\", \"/var/www/html\");\ndefine(\"NAME_BD\", \"" . $_POST["bd_name"] . "\");\ndefine(\"INSTALADO\", \"\");\ndefine(\"USER_BD\", \"" . $_POST["bd_user"] . "\");\ndefine(\"PASSWORD_BD\", \"" . $_POST["bd_pass"] . "\");\ndefine(\"HOST_BD\", \"" . $_POST["bd_host"] . "\");\ndefine(\"PORT_BD\", \"" . $_POST["bd_port"] . "\");\ndefine(\"BACKUP_LIMIT\",14);\ndefine(\"SSL_KEY\",\"" . SSL_KEY . "\");\ndefine(\"SSL_CRT\",\"" . SSL_CRT . "\");\ndefine(\"ROOT_BD_PASSWORD\",\"" . ROOT_BD_PASSWORD . "\");\n?>";
                file_put_contents("/var/www/html/admin/ajax/db/config.php", $texto);
                $salida = ["estado" => "exito", "salida" => "La conexión Mysql es Correcta"];
                header("Content-Type: application/json");
                echo json_encode($salida, JSON_UNESCAPED_UNICODE);
                exit;
            } catch (PDOException $e) {
                $salida = ["estado" => "error", "salida" => "<b>Los datos de conexión Mysql son incorrectos: </b>" . $e->getMessage()];
                header("Content-Type: application/json");
                echo json_encode($salida, JSON_UNESCAPED_UNICODE);
                exit;
            }
            break;
        case 2:
            $salida = _obfuscated_0D020F15092F192E312C0D08021037211E31172F160711_($_POST["licencia_user"], $_POST["licencia_pass"]);
            if($salida["estado"] == "exito") {
                if(52 < $salida["revision"]) {
                    exec("wget -q " . $salida["instalador"] . " -O /var/www/html/Install_rev_" . $salida["revision"] . ".zip > /dev/null");
                    if(file_exists("/var/www/html/Install_rev_" . $salida["revision"] . ".zip")) {
                        exec("unzip -o /var/www/html/Install_rev_" . $salida["revision"] . ".zip -d /var/www/html/");
                        if(file_exists("/var/www/html/install_rev_" . $salida["revision"] . ".sql")) {
                            exec("sudo mysql --user=\"root\" --port=\"" . PORT_BD . "\" --password=\"" . ROOT_BD_PASSWORD . "\" --host=" . HOST_BD . " " . NAME_BD . " < /var/www/html/install_rev_" . $salida["revision"] . ".sql");
                            unlink("/var/www/html/install_rev_" . $salida["revision"] . ".sql");
                            unlink("/var/www/html/Install_rev_" . $salida["revision"] . ".zip");
                            $texto = "<?php\n\tdefine(\"root\", \"/var/www/html\");\n\tdefine(\"INSTALADO\", \"YES\");\n\tdefine(\"NAME_BD\", \"" . NAME_BD . "\");\n\tdefine(\"USER_BD\", \"" . USER_BD . "\");\n\tdefine(\"PASSWORD_BD\", \"" . PASSWORD_BD . "\");\n\tdefine(\"HOST_BD\", \"" . HOST_BD . "\");\n\tdefine(\"PORT_BD\", \"" . PORT_BD . "\");\n\tdefine(\"BACKUP_LIMIT\",14);\n\tdefine(\"SSL_KEY\",\"" . SSL_KEY . "\");\n\tdefine(\"SSL_CRT\",\"" . SSL_CRT . "\");\n\tdefine(\"ROOT_BD_PASSWORD\",\"" . ROOT_BD_PASSWORD . "\");\n\t?>";
                            file_put_contents("/var/www/html/admin/ajax/db/config.php", $texto);
                            $salida = ["estado" => "exito", "salida" => "Revision 53.", "rev" => "53", "u" => $_POST["licencia_user"], "p" => $_POST["licencia_pass"]];
                            header("Content-Type: application/json");
                            echo json_encode($salida, JSON_UNESCAPED_UNICODE);
                            exit;
                        }
                        $salida = ["estado" => "error", "salida" => "El instalador no fue descomprimido correctamente, intente nuevamente."];
                        header("Content-Type: application/json");
                        echo json_encode($salida, JSON_UNESCAPED_UNICODE);
                        exit;
                    }
                    $salida = ["estado" => "error", "salida" => "No es posible descargar el instalador, intente nuevamente.<br> Si el error persiste contacte con soporte Mikrowisp."];
                    header("Content-Type: application/json");
                    echo json_encode($salida, JSON_UNESCAPED_UNICODE);
                    exit;
                }
                exec("wget -q " . $salida["instalador"] . " -O /var/www/html/Install_rev_" . $salida["revision"] . ".zip > /dev/null");
                if(file_exists("/var/www/html/Install_rev_" . $salida["revision"] . ".zip")) {
                    exec("unzip -o /var/www/html/Install_rev_" . $salida["revision"] . ".zip -d /var/www/html/");
                    if(file_exists("/var/www/html/install_rev_" . $salida["revision"] . ".sql")) {
                        exec("sudo mysql --user=\"root\" --port=\"" . PORT_BD . "\" --password=\"" . ROOT_BD_PASSWORD . "\" --host=" . HOST_BD . " " . NAME_BD . " < /var/www/html/install_rev_" . $salida["revision"] . ".sql");
                        unlink("/var/www/html/install_rev_" . $salida["revision"] . ".sql");
                        unlink("/var/www/html/Install_rev_" . $salida["revision"] . ".zip");
                        $texto = "<?php\ndefine(\"root\", \"/var/www/html\");\ndefine(\"INSTALADO\", \"YES\");\ndefine(\"NAME_BD\", \"" . NAME_BD . "\");\ndefine(\"USER_BD\", \"" . USER_BD . "\");\ndefine(\"PASSWORD_BD\", \"" . PASSWORD_BD . "\");\ndefine(\"HOST_BD\", \"" . HOST_BD . "\");\ndefine(\"PORT_BD\", \"" . PORT_BD . "\");\ndefine(\"BACKUP_LIMIT\",14);\ndefine(\"SSL_KEY\",\"" . SSL_KEY . "\");\ndefine(\"SSL_CRT\",\"" . SSL_CRT . "\");\ndefine(\"ROOT_BD_PASSWORD\",\"" . ROOT_BD_PASSWORD . "\");\n?>";
                        file_put_contents("/var/www/html/admin/ajax/db/config.php", $texto);
                        require_once "../admin/ajax/db/MysqliDb.php";
                        require_once "../admin/ajax/db/funciones.php";
                        $db->where("setting", "passlic");
                        $db->update("tblconfiguration", ["value" => $_POST["licencia_pass"]]);
                        $db->where("setting", "userlic");
                        $db->update("tblconfiguration", ["value" => $_POST["licencia_user"]]);
                        $db->where("setting", "url_portal");
                        $db->update("tblconfiguration", ["value" => "http://" . $_SERVER["SERVER_ADDR"]]);
                        _obfuscated_0D0A022518073826331433062C11270B1F0C120F2C3411_();
                        _obfuscated_0D295C021234010A0A25353B1D233315112626151F2932_();
                        _obfuscated_0D131A25300306365C05170E3119121A1E0E3919263101_();
                        if(function_exists("validalicencia")) {
                            _obfuscated_0D5C312B5C0B3E0333303C2D32330C1D1F162E0E033D32_();
                        }
                        $salida = ["estado" => "exito", "salida" => "El sistema fué instalado correctamente."];
                        header("Content-Type: application/json");
                        echo json_encode($salida, JSON_UNESCAPED_UNICODE);
                        exit;
                    }
                    $salida = ["estado" => "error", "salida" => "El instalador no fue descomprimido correctamente, intente nuevamente."];
                    header("Content-Type: application/json");
                    echo json_encode($salida, JSON_UNESCAPED_UNICODE);
                    exit;
                }
                $salida = ["estado" => "error", "salida" => "No es posible descargar el instalador, intente nuevamente.<br> Si el error persiste contacte con soporte Mikrowisp."];
                header("Content-Type: application/json");
                echo json_encode($salida, JSON_UNESCAPED_UNICODE);
                exit;
            }
            $salida = ["estado" => "error", "salida" => $salida["data"]];
            header("Content-Type: application/json");
            echo json_encode($salida, JSON_UNESCAPED_UNICODE);
            exit;
            break;
        default:
            exit;
    }
} else {
    echo "<!doctype html>\n<html>\n<head>\n<meta charset=\"UTF-8\">\n<title>Instalación Mikrowisp V6</title>\n<meta content=\"width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no\" name=\"viewport\" />\n<meta content=\"\" name=\"description\" />\n<meta content=\"Mikrowisp SAC\" name=\"author\" />\n<link rel=\"shortcut icon\" type=\"image/ico\" href=\"../admin/favicon.ico\">\n<link rel=\"apple-touch-icon\" sizes=\"180x180\" href=\"../admin/apple-touch-icon.png\">\n<link rel=\"mask-icon\" href=\"../admin/safari-pinned-tab.svg\" color=\"#5bbad5\">\n\t\n\t<!-- ================== BEGIN BASE CSS STYLE ================== -->\n\t<link href=\"https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700\" rel=\"stylesheet\" />\n\t<link href=\"../admin/plugins/jquery-ui/jquery-ui.min.css\" rel=\"stylesheet\" />\n\t<link href=\"../admin/plugins/bootstrap/4.1.0/css/bootstrap.min.css\" rel=\"stylesheet\" />\n\t<link href=\"../admin/plugins/font-awesome/css/all.min.css\" rel=\"stylesheet\" />\n\t<link href=\"../admin/plugins/animate/animate.min.css\" rel=\"stylesheet\" />\n\t<link href=\"../admin/css/default/style.min.css\" rel=\"stylesheet\" />\n\t<link href=\"../admin/css/default/style-responsive.min.css\" rel=\"stylesheet\" />\n\t<link href=\"../admin/css/default/theme/blue.css\" rel=\"stylesheet\" id=\"theme\" />\n    <link href=\"../admin/plugins/gritter/css/jquery.gritter.css\" rel=\"stylesheet\" />\n\t<link href=\"../admin/plugins/jquery-smart-wizard/src/css/smart_wizard.css\" rel=\"stylesheet\" />\n\t<link href=\"../admin/plugins/parsley/src/parsley.css\" rel=\"stylesheet\" />\n\t\n\t<!-- ================== BEGIN BASE JS ================== -->\n\t<script src=\"../admin/plugins/pace/pace.min.js\"></script>\n\t<!-- ================== END BASE JS ================== -->\n\t\n<style type=\"text/css\">\n.form-control-with-bg .form-control, .form-control.form-control-with-bg {\n    background: #ffffff;\n}\n\t\n::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */\n    color: #e3e7ea !important;\n    opacity: 1; /* Firefox */\n}\n\n:-ms-input-placeholder { /* Internet Explorer 10-11 */\n    color: #e3e7ea !important;\n}\n\n::-ms-input-placeholder { /* Microsoft Edge */\n    color: #e3e7ea !important;\n}\n\t\n.fa-spin {\n    animation: fa-spin 2s infinite linear;\n    font-size: 35px;\n}\n\t\n.alerta-exito .gritter-close {\n    color: #176f48 !important;\n}\n\n.alerta-exito .gritter-title {\n    font-size: 13px!important;\n\tfont-weight:bold !important;\n}\n\n.alerta-warning .gritter-bottom,.alerta-warning .gritter-item,.alerta-warning .gritter-top {\n background:rgba(228, 156, 38, 0.85)!important;\n}\n\n.alerta-warning .gritter-item p,.alerta-exito .gritter-item p,.alerta-error .gritter-item p {\n    color: #FFF;\n    font-size: 12px;\n}\n\n.alerta-warning .gritter-close {\n    color: #176f48 !important;\n}\n\n.alerta-warning .gritter-title {\n    font-size: 13px!important;\n\tfont-weight:bold !important;\n}\n\n.gritter-image i{\n\tfont-size: 45px;\n    color: #fff;\n\t}\n\n\t.alerta-loader .gritter-image i{\n\tfont-size: 30px;\n    color: #fff;\n\t}\n\t\n\t.alerta-loader .gritter-image {\n    width: 35px;\n    height: 35px;\n    float: left;\n}\n\n.gritter-close:before, .gritter-light .gritter-close:before {\n\tfont-family:Font Awesome\\ 5 Free !important;\n    content: \"\\f00d\" !important;\n\tfont-size: 16px;\n}\n\n.alerta-exito .gritter-bottom,.alerta-exito .gritter-item,.alerta-exito .gritter-top {\nbackground: rgba(23, 152, 137, 0.8)!important;\n}\n\n.alerta-error .gritter-bottom,.alerta-error .gritter-item,.alerta-error .gritter-top {\nbackground: rgba(197, 74, 74, 0.8)!important;\n}\n\n\t\n.alerta-exito .gritter-close,.alerta-exito .gritter-light .gritter-close {\n    border-left: 1px solid #227975;\n}\n\n.alerta-error .gritter-close,.alerta-error .gritter-light .gritter-close {\n    border-left: 1px solid #943f3f;\n}\n\t\n\t\n.alerta-exito .gritter-close:before {\n    color: #1d5e5f !important\n}\n\t\n.alerta-error .gritter-close:before {\n    color: #6d1c1c !important;\n}\n\t\n.sw-main .step-contentx {\n    display: block; \n    position: relative;\n    margin: 0;\n}\n\t\n</style>\n</head>\n\n<body>\n\t<!-- begin #page-loader -->\n\t<div id=\"page-loader\" class=\"fade show\"><span class=\"spinner\"></span></div>\n\t<!-- end #page-loader -->\n\t\n\t<!-- begin #page-container -->\n\t<div id=\"page-container\" class=\"fade page-sidebar-fixed page-header-fixed\">\n\t\t<!-- begin #header -->\n\t\t<div id=\"header\" class=\"header navbar-default\">\n\t\t\t<!-- begin navbar-header -->\n\t\t\t<div class=\"navbar-header\">\n\t\t\t\t<a href=\"#\" class=\"navbar-brand\"><img src=\"https://mikroimage.net/images/2018/05/26/logo_1669885_print179371efd24a0086.png\" class=\"logo-admin\" style=\"height:38px; width:auto\"></a>\n\t\t\t</div>\n\t\t\t<!-- end navbar-header -->\n\t\t\t\n\n\t\t</div>\n\t\t<!-- end #header -->\n\n\t\t<!-- begin #content -->\n\t\t<div id=\"content\" class=\"content\" style=\"margin-left: auto;\">\n\n\t\t\t<!-- begin wizard-form -->\n<form  enctype=\"multipart/form-data\" method=\"POST\" name=\"form-wizard\" class=\"form-control-with-bg\">\n<!-- begin wizard -->\n<div id=\"wizard\">\n<!-- begin wizard-step -->\n<ul>\n\t\n<li class=\"col-md-3 col-sm-4 col-6\">\n<a href=\"#step-1\">\n<span class=\"number\">1</span> \n<span class=\"info text-ellipsis\">Base de datos\n<small class=\"text-ellipsis\">Datos de conexión</small>\n</span>\n</a>\n</li>\n\t\n<li class=\"col-md-3 col-sm-4 col-6\">\n<a href=\"#step-2\">\n<span class=\"number\">2</span> \n<span class=\"info text-ellipsis\">Instalación\n<small class=\"text-ellipsis\">Datos de licencia + Instalación</small>\n</span>\n</a>\n</li>\n\t\n<li class=\"col-md-3 col-sm-4 col-6\">\n<a href=\"#step-3\">\n<span class=\"number\">3</span> \n<span class=\"info text-ellipsis\">Finalizar\n<small class=\"text-ellipsis\">instalación terminada</small>\n</span>\n</a>\n</li>\n\t\n\n\t\t\t\t\t</ul>\n\n<div>\n\n<div id=\"step-1\">\n\n<fieldset>\n<div class=\"row\">\n<div class=\"col-md-8 offset-md-2\">\n<legend class=\"no-border f-w-700 p-b-0 m-t-0 m-b-20 f-s-16 text-inverse\">Datos servidor Mysql</legend>\n\n<div class=\"form-group row m-b-10\">\n<label class=\"col-md-3 col-form-label text-md-right\">Dirección servidor<span class=\"text-danger\">*</span></label>\n<div class=\"col-md-6\">\n<input type=\"text\" name=\"bd_host\" placeholder=\"Ejm: localhost\" data-parsley-group=\"step-1\" data-parsley-required=\"true\" class=\"form-control\" value=\"";
    if(HOST_BD) {
        echo HOST_BD;
    }
    echo "\"/>\n</div>\n</div>\n\n<div class=\"form-group row m-b-10\">\n<label class=\"col-md-3 col-form-label text-md-right\">Puerto servidor<span class=\"text-danger\">*</span></label>\n<div class=\"col-md-6\">\n<input type=\"text\" name=\"bd_port\" placeholder=\"Ejm: 3306\" data-parsley-group=\"step-1\" data-parsley-required=\"true\" class=\"form-control\" value=\"";
    if(PORT_BD) {
        echo PORT_BD;
    }
    echo "\"/>\n</div>\n</div>\n\t\n\t\n<div class=\"form-group row m-b-10\">\n<label class=\"col-md-3 col-form-label text-md-right\">Base de datos <span class=\"text-danger\">*</span></label>\n<div class=\"col-md-6\">\n<input type=\"text\" name=\"bd_name\" placeholder=\"Ejm: Mikrowisp6\" data-parsley-group=\"step-1\" data-parsley-required=\"true\" class=\"form-control\" value=\"";
    if(NAME_BD) {
        echo NAME_BD;
    }
    echo "\"  readonly/>\n</div>\n</div>\n\n<div class=\"form-group row m-b-10\">\n<label class=\"col-md-3 col-form-label text-md-right\">Usuario Mysql <span class=\"text-danger\">*</span></label>\n<div class=\"col-md-6\">\n<input type=\"text\" name=\"bd_user\" placeholder=\"Ejm: user1\" data-parsley-group=\"step-1\" data-parsley-required=\"true\" class=\"form-control\" value=\"";
    if(USER_BD) {
        echo USER_BD;
    }
    echo "\" />\n</div>\n</div>\n\t\n\t\n<div class=\"form-group row m-b-10\">\n<label class=\"col-md-3 col-form-label text-md-right\">Contraseña Mysql <span class=\"text-danger\">*</span></label>\n<div class=\"col-md-6\">\n<input type=\"text\" name=\"bd_pass\" placeholder=\"Ejm: 12345\" data-parsley-group=\"step-1\" data-parsley-required=\"true\" class=\"form-control\" value=\"";
    if(PASSWORD_BD) {
        echo PASSWORD_BD;
    }
    echo "\" />\n</div>\n</div>\n\t\n<div class=\"form-group m-t-20 m-b-10 text-center\">\n<button type=\"button\" class=\"btn btn-white btn-step-1\" onClick=\"step('1')\"><img src=\"../admin/favicon.ico\" height=\"22px\"> Continuar <i class=\"fas fa-lg fa-angle-double-right\"></i></button>\n</div>\t\n\n</div>\n</div>\n\t\n</fieldset>\n</div>\n\n<div id=\"step-2\">\n\n<fieldset>\n<div class=\"row\">\n<div class=\"col-md-8 offset-md-2\">\n<legend class=\"no-border f-w-700 p-b-0 m-t-0 m-b-20 f-s-16 text-inverse\">Datos de su Licencia Mikrowisp<br> <small> Puede obtener sus datos desde el portal clientes de <a href=\"https://mikrosystem.net/clientes/\" target=\"_blank\"><b>Nuestra web Mikrowisp.net</b></a></small></legend>\n\n<div class=\"form-group row m-b-10\">\n<label class=\"col-md-3 col-form-label text-md-right\">Correo electrónico<span class=\"text-danger\">*</span></label>\n<div class=\"col-md-6\">\n<input type=\"text\" name=\"licencia_user\" placeholder=\"Ejm: carlos@correo.com\" data-parsley-group=\"step-2\" data-parsley-required=\"true\" class=\"form-control\"/>\n</div>\n</div>\n\n<div class=\"form-group row m-b-10\">\n<label class=\"col-md-3 col-form-label text-md-right\">Token Licencia<span class=\"text-danger\">*</span></label>\n<div class=\"col-md-6\">\n<input type=\"text\" name=\"licencia_pass\" placeholder=\"Ejm: jhasdkashdkjhs3uusasd\" data-parsley-group=\"step-2\" data-parsley-required=\"true\" class=\"form-control\"/>\n</div>\n</div>\n\n\t\n<div class=\"form-group m-t-20 m-b-10 text-center\">\n<button type=\"button\" class=\"btn btn-white btn-step-2\" onClick=\"step('2')\"><img src=\"../admin/favicon.ico\" height=\"22px\"> Iniciar Instalación <i class=\"fas fa-lg fa-angle-double-right\"></i></button>\n</div>\t\n\t\n\t\n\n</div>\n</div>\n\t\n</fieldset>\n</div>\n\n<div id=\"step-3\">\n\t\t\t\t\t\t\t<div class=\"jumbotron m-b-0\">\n\t\t\t\t\t\t\t\t<h2 class=\"text-inverse\">Instalación Completa.</h2>\n\t\t\t\t\t\t\t\t<p class=\"m-b-30 f-s-14\">Mikrowisp V6 fué instalado correctamente.<br>Mikrowisp cuenta con 2 portales uno administrativo y otro para sus clientes.<br>\n\t\t\t\t\t\t\t\t<b>1.- Acceso Administrador :</b> http://";
    echo $_SERVER["HTTP_HOST"];
    echo "/admin  (User y Pass= admin)<br>\n\t\t\t\t\t\t\t\t<b>2.- Acceso Clientes:</b> http://";
    echo $_SERVER["HTTP_HOST"];
    echo "/cliente<br>\n\t\t\t\t\t\t\t\t</p>\n\t\t\t\t\t\t\t\t<p><a href=\"http://";
    echo $_SERVER["HTTP_HOST"];
    echo "/admin/login\" class=\"btn btn-primary btn-md\">Ingresar al Administrador</a></p>\n\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t</div>\n\n</div>\n\t\t\t\t\t<!-- end wizard-content -->\n</div>\n\t\t\t\t<!-- end wizard -->\n</form>\n\t\t\t<!-- end wizard-form -->\n\t\t\t\n\t\t</div>\n\t\t<!-- end #content -->\n\t\t\n\t\t<!-- begin scroll to top btn -->\n\t\t<a href=\"javascript:;\" class=\"btn btn-icon btn-circle btn-success btn-scroll-to-top fade\" data-click=\"scroll-top\"><i class=\"fa fa-angle-up\"></i></a>\n\t\t<!-- end scroll to top btn -->\n\t</div>\n\t<!-- end page container -->\t\n\n\t    <!-- ================== BEGIN BASE JS ================== -->\n\t<script src=\"../admin/plugins/jquery/jquery-3.2.1.min.js\"></script>\n\t<script src=\"../admin/plugins/jquery-ui/jquery-ui.min.js\"></script>\n\t<script src=\"../admin/plugins/bootstrap/4.1.0/js/bootstrap.bundle.min.js\"></script>\n\t\n\t<script src=\"../admin/plugins/moment/moment.min.js\"></script>\n\t<script src=\"../admin/plugins/jquery/jquery-migrate-1.1.0.min.js\"></script>\n\n\t<!--[if lt IE 9]>\n\t\t<script src=\"../admin/crossbrowserjs/html5shiv.js\"></script>\n\t\t<script src=\"../admin/crossbrowserjs/respond.min.js\"></script>\n\t\t<script src=\"../admin/crossbrowserjs/excanvas.min.js\"></script>\n\t<![endif]-->\n\t<script src=\"../admin/plugins/jquery-hashchange/jquery.hashchange.min.js\"></script>\n\t<script src=\"../admin/plugins/slimscroll/jquery.slimscroll.min.js\"></script>\n\t<script src=\"../admin/plugins/js-cookie/js.cookie.js\"></script>\n\t<!-- ================== END BASE JS ================== -->\n    \n    <script src=\"../admin/plugins/gritter/js/jquery.gritter.min.js\"></script>\n\t<script type=\"text/javascript\" src=\"../admin/js/datatables.min.js\"></script>\n\t<script type=\"text/javascript\" src=\"../admin/plugins/parsley/dist/parsley.js\"></script>\n\t<script type=\"text/javascript\" src=\"../admin/plugins/jquery-smart-wizard/src/js/jquery.smartWizard.js\"></script> \n\t<!-- ================== END BASE JS ================== -->\n\t\n\t<!-- ================== BEGIN PAGE LEVEL JS ================== -->\n\t<script src=\"../admin/js/theme/default.min.js\"></script>\n\t<script src=\"../admin/js/apps.min.js?v=6.0\"></script>\n    <script src=\"../admin/js/initial.min.js\"></script>\n\t<script src=\"../admin/js/mikrowisp.js\"></script>\n\t\n\t<script>\n\t\t\n\$('form[name=\"form-wizard\"]')\n  .submit( function( e ) {\n\$('.btn-step-3').prop( \"disabled\",true);\n    \$.ajax( {\n      url: 'index?action=backup',\n      type: 'POST',\n      data: new FormData( this ),\n      processData: false,\n      contentType: false,\n\tsuccess: function(data){\n\$('.btn-step-3').prop( \"disabled\",false);\n\$('#gritter-notice-wrapper').remove();\nif(data['estado']=='exito'){\nalerta('exito',data['salida'])\n\$('#wizard').smartWizard('next');\n}else{\nalerta('error',data['salida'])\n}\t\t\n\t},\nbeforeSend: function() {\nalerta('loader','Procesando Backup, esta operación puede tardar algunos minutos...')\n}\n    });\n    e.preventDefault();\n  } );\n\t\t\nfunction step(id){\nvar res = \$('form[name=\"form-wizard\"]').parsley().validate('step-' + id);\nif(!res){\nreturn false;\n}\t\n\n\$('.btn-step-'+id).prop( \"disabled\", true );\n\$.ajax({\ntype: \"POST\",\nurl: 'index?action=valid',\ndata: 'paso='+id+'&'+\$('form[name=\"form-wizard\"]').serialize(),\nsuccess: function(data){\n\$('.btn-step-'+id).prop( \"disabled\",false);\n\$('#gritter-notice-wrapper').remove();\nif(data['estado']=='exito'){\n\tif(data['rev']){\n\n\t\t\$.post( \"update\", { u: data['u'], p: data['p'] })\n\t.done(function( data2 ) {\n\t\talerta('exito',data['salida'])\n\t\t\$('#wizard').smartWizard('next');\n\t});\n\t}else{\n\t\talerta('exito',data['salida'])\n\t\t\$('#wizard').smartWizard('next');\n\t}\n\n}else{\nalerta('error',data['salida'])\n}\n},\nbeforeSend: function() {\nalerta('loader','Instalando Mikrowisp...')\n},\nerror: function(response) {\n\$('#gritter-notice-wrapper').remove();\nalerta('error500',response['responseText']);\n}})\n\t}\t\t\t\t\n\tvar handleBootstrapWizardsValidation = function() {\n\t\"use strict\";\n\t\$('#wizard').smartWizard({ \n\t\tselected: 0, \n\t\ttheme: 'default',\n\t\ttransitionEffect:'',\n\t\ttransitionSpeed: 0,\n\t\tuseURLhash: false,\n\t\tbackButtonSupport:false,\n\t\tshowStepURLhash: false,\n   \n\t\tanchorSettings: {\n                anchorClickable: false, // Enable/Disable anchor navigation\n            },\n\t\ttoolbarSettings: {\n\t\ttoolbarPosition: 'bottom',\n\t\tshowPreviousButton: false,\n\t\tshowNextButton: false,\t\n\t\t\t//<button class=\"btn btn-default sw-btn-next\" type=\"button\">Siguiente</button>\n\t\t}\n\t});\n\n};\n\nvar FormWizardValidation = function () {\n\t\"use strict\";\n    return {\n        //main function\n        init: function () {\n            handleBootstrapWizardsValidation();\n        }\n    };\n}();\n\t\t\$(document).ready(function() {\n\t\t\tApp.init();\n\t\t\thandleBootstrapWizardsValidation();\n\t\t});\n\t\t\n\$('input[name=\"file_bk\"]').bind('change', function() {\n\t\nif(\$('input[name=\"file_bk\"]').val()==''){\n\$('.btn-step-3').html('<img src=\"../admin/favicon.ico\" height=\"22px\"> Continuar <i class=\"fas fa-lg fa-angle-double-right\"></i>');\nreturn false;\n}\nvar fileSize = this.files[0].size;\n\n\$('.btn-step-3').html('<img src=\"../admin/favicon.ico\" height=\"22px\"> Subir & Restaurar <i class=\"fas fa-lg fa-angle-double-right\"></i>');\n\n})\n\t</script>\n\t\n</body>\n</html>";
}
function _obfuscated_0D38051506161D1033032E2F2437333B01330B405B0E11_($action, $string)
{
    $_obfuscated_0D2804290F123B3D07352629341E0D1E2510370D130C01_ = false;
    $_obfuscated_0D2D0B0205323335131E27113C2B342208173C263B3311_ = "AES-256-CBC";
    $_obfuscated_0D081B2A1B09262A1A1E342D15392B1F3C132C32042932_ = "213123123";
    $_obfuscated_0D03133E1C16230C0A013C211F042B13031D3C1B035B22_ = "jjdhdgdg83d98897ggj@";
    $key = hash("sha256", $_obfuscated_0D081B2A1B09262A1A1E342D15392B1F3C132C32042932_);
    $_obfuscated_0D0C283B3F2A350223342C07212E253828323C401E2422_ = substr(hash("sha256", $_obfuscated_0D03133E1C16230C0A013C211F042B13031D3C1B035B22_), 0, 16);
    if($action == "encode") {
        $_obfuscated_0D2804290F123B3D07352629341E0D1E2510370D130C01_ = _obfuscated_0D381D3D140F0B402E11121336040C29362D1F2B372901_($string, $_obfuscated_0D2D0B0205323335131E27113C2B342208173C263B3311_, $key, 0, $_obfuscated_0D0C283B3F2A350223342C07212E253828323C401E2422_);
        $_obfuscated_0D2804290F123B3D07352629341E0D1E2510370D130C01_ = base64_encode($_obfuscated_0D2804290F123B3D07352629341E0D1E2510370D130C01_);
    } elseif($action == "decode") {
        $_obfuscated_0D2804290F123B3D07352629341E0D1E2510370D130C01_ = _obfuscated_0D10071228092940145B12263C02122809075B043E0F32_(base64_decode($string), $_obfuscated_0D2D0B0205323335131E27113C2B342208173C263B3311_, $key, 0, $_obfuscated_0D0C283B3F2A350223342C07212E253828323C401E2422_);
    }
    return $_obfuscated_0D2804290F123B3D07352629341E0D1E2510370D130C01_;
}
function _obfuscated_0D020F15092F192E312C0D08021037211E31172F160711_($u, $p)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://mkws6.net/getv.php");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ["u" => _obfuscated_0D38051506161D1033032E2F2437333B01330B405B0E11_("encode", $u), "p" => _obfuscated_0D38051506161D1033032E2F2437333B01330B405B0E11_("encode", $p)]);
    return json_decode(curl_exec($ch), JSON_UNESCAPED_UNICODE);
}

?>