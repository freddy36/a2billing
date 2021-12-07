<?PHP

$allowed_hosts = [
	"109.236.159.41",
	"109.236.159.42",
#	"", # cli
];

$config = "/etc/a2billing.conf";




$ip = $_SERVER['REMOTE_ADDR'];
if (!in_array($ip, $allowed_hosts))
{
	header('HTTP/1.0 403 Forbidden');
	exit($ip." is not allowed");
	
}

$ini = parse_ini_file($config, $process_sections = true);

$dbconf = $ini['database'];

$conn = new mysqli($dbconf['hostname'], $dbconf['user'], $dbconf['password'], $dbconf['dbname']);

if ($conn->connect_error)
{
	throw new Exception("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT did FROM `cc_did` WHERE activated = 1";
$result = $conn->query($sql);

$dids = [];

while($row = $result->fetch_assoc())
{
	$dids[] = $row['did'];
}

$conn->close();


header('Content-Type: application/json');
echo json_encode($dids);

?>
