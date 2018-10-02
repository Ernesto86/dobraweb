<?php
require_once 'cls_acceso_datos_mysql.php';

class cls_usuario_mysql extends cls_acceso_datos_mysql
{
    protected $user_online = '';
    protected $dir_img = '';

    function set_usuario_online($value)
    {
        $this->user_online = trim($value);
    }

    function set_dir_image($value)
    {
        $this->dir_img = trim($value);
    }

    function fn_update_user_online($stado)
    {
        $this->query = 'UPDATE ci_users SET online=' . intval($stado) . ' WHERE user_name =' . "'" . $this->user_online . "'";
        return $this->execute();
    }

    function update_image()
    {
        $this->query = "UPDATE ci_users SET dir_img='" . $this->dir_img . "' WHERE user_name ='" . $this->user_online . "'";
        return $this->execute();
    }

    function fn_ruta_img()
    {
        $this->query = 'SELECT dir_img img FROM ci_users WHERE user_name=' . "'" . $this->user_online . "' LIMIT 1;";
        $this->execute();
        if ($this->numreg()) {
            $cmp = $this->campos();
            return trim($cmp['img']);
        }
        return '../images/user/usu.gif';
    }

    function fn_usuarios_online()
    {
        $this->query = 'SELECT user_id id,user_name user,dir_img img FROM ci_users WHERE status=1 AND online=1 AND user_name!=' . "'" . $this->user_online . "' ORDER BY user_name";
        $this->execute();
        if ($this->numreg() > 0) {
            echo '<ul>';
            while (($r = @mysqli_fetch_assoc($this->rcs))) {
                $user = strval($r['user']);
                $img = strval($r['img']);
                echo '<li><a href="javascript:void(0)" onClick="javascript:chatWith(' . "'$user'" . ');">';
                echo '<div><img src="';
                echo $img ? $img : '../images/user/user_chat.png';
                echo '" id="foto_chat"/> ' . $user . '</div></a></li>';
            }
            echo '</ul>';
        } else echo 'No se Encontro Usuarios Online.';
    }
}

?>