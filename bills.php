<?php
$con=mysqli_connect("127.0.0.1","root","", "monsef");
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
?>

<?php
$result = mysqli_query($con, "SELECT BASE FROM TAXLINES ORDER BY BASE ASC");
#$result = mysqli_query($con, 'SELECT RECEIPT, BASE FROM (SELECT NULL AS BASE, NULL AS total, Null as found, NULL AS RECEIPT FROM dual WHERE (@total := 0 OR @found := 0) UNION SELECT BASE, @total AS tota$

$receipts ="";
$sold = 0;
echo "<table><tr><td width='100px'><h2>Nr. </td><td width='200px'><h2>Salg pr. Kvittering</td><td><h2>Salg Total</td></tr>";
$i = 1;
while($row = mysqli_fetch_array($result))
  {
  echo "<tr><td><h2>" . $i++ . "</td>";
  echo "<td><h3>" . round($row['BASE'],2) . "</td>";
  $sold = $sold + $row['BASE'];
  echo  "<td><h3>" . number_format($sold, 2) . "</td></tr>";

}
echo "</table>";

?>
