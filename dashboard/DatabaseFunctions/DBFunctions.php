<?php

class DBaccess
{
    private $Hostname;
    public $Username;
    protected $Password;
    private $Database;
    public $DBConnect;

    public function __construct()
    {
        if(isset($_COOKIE['AUsername']) && !empty($_COOKIE)){
            $this->Username = $_COOKIE['AUsername'];
            if (file_exists('./.adminfiles/adminDB.authDB')) {
                file_get_contents()
            };
        }elseif (isset($_POST['Username'],$_POST['Database'],$_POST['Password'],$_POST['Hostname']) && !empty($_POST)) {
            $this->Hostname = $_POST['AHostname'];
            $this->Username = $_POST['AUsername'];
            $this->Database = $_POST['ADatabase'];
            $this->Password = $_POST['APassword'];  
            setcookie("AHostname", $this->Hostname, time()+((3600*24)*365), "/Admin/", "example.com", 1);
        }else{
            header('Location: '.$_SERVER['HTTP_HOST'].'/EmployeeRosterPHP/dashboard/DatabaseFunctions/chkdb.html');
            exit;
        }
        $this->initialize($this->Hostname, $this->Username, $this->Password, $this->Database);
    }



    protected function initialize()
    {
        try {
            $this->DBConnect = new PDO("mysql:host=$Hostname;dbname=$Database", $Username, $Password, array(PDO::ATTR_PERSISTENT => true));
            // set the PDO error mode to exception
            $this->DBConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "<p>Connected successfully</p>";
        } catch(PDOException $eror) {
            echo "Connection failed: " . $eror->getMessage();
        }
        $userATable = "CREATE TABLE IF NOT EXISTS ".strtolower($this->Database).
            ".UserAdmins(username TEXT NOT NULL,
            password TEXT NOT NULL,
            UNIQUE (username)
            ) ENGINE= InnoDB";
        try {
            $SendDB = $this->DBConnect->prepare($userATable);
            $SendDB->execute();
        } catch(PDOException $eror) {
            echo $eror;
        }
        return $this->DBConnect;
    }
    


    protected function PassValidate(string $password,bool $ChkOrHash = True){
        if ($ChkOrHash) {
            $Phash=password_hash($password,PASSWORD_BCRYPT,['cost' => 10]);
            return $Phash;
            
        }else{
            password_verify($password,);
        }
    }
    public function Adduser(string $username, string $Password = GetPass($_POST['password'])){
        $adduser = "INSERT INTO IF NOT EXISTS useradmins (username,password) VALUES ($username,$password)"
    }
    public function printDB()
    {
        print_r($this->DBConnect);
    }
}
$aDBF = new DBaccess($_POST['hostname'], $_POST['username'], $_POST['password'], $_POST['database']);
$aDBF->printDB();
