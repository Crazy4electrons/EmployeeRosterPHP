<?php
$username = null;
function setloginimage($username = null, $Image = null)
{
    if ($Image == null) {
        if ($username == null) {
            return "Hi";
        } else {
            $words = explode(" ", $username);
            $first_letters = array();
            foreach ($words as $word) {
                $first_letters[] = substr($word, 0, 1);
            }
            $first_letters_string = implode("", $first_letters);

            return $first_letters_string;
        }
    } else {
        return $Image;
    }
}
?>


<div id="loginStatus" class="">

    <span><?php echo (($username == null) ? 'Hi, sign in here. <i class="fa fa-smile-o" aria-hidden="true"></i>' : $username)  ?></span>

    <span id="userIdImg"> <?php echo setloginimage($username) ?> </span>

</div>
<link rel="stylesheet" href="dashboard/modules/loginStatus/Styles/loginStatus.css" media="print" onload="this.media='all'">