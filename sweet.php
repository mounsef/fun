<?php


//$q= isset($_GET["q"]) ? $_GET["q"] : 3000;
$q= $_GET["q"];
$con=mysqli_connect("127.0.0.1","root","", "monsef");
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

$result = mysqli_query($con, 'SELECT RECEIPT, BASE FROM (SELECT NULL AS BASE, NULL AS total, Null as found, NULL AS RECEIPT FROM dual WHERE (@total := 0 OR @found := 0) UNION SELECT BASE, @total AS total, @found := 1 AS found, RECEIPT from (select * from taxlines order by BASE asc) as ticket_new WHERE (@total := @total + BASE) AND @total <= ' . "$q" . ' AND BASE <= ' . "$q" . ' UNION SELECT BASE, BASE AS total, 0 AS found, RECEIPT FROM (select * from taxlines order by BASE asc) as ticket_new WHERE IF(@found = 0, @found := 1, 0)) as new');

$receipts ="";
$sold  ="";
while($row = mysqli_fetch_array($result))
  {
  //echo $row['RECEIPT'];
  $receipts = $receipts . ", '" . $row['RECEIPT'] . "' ";
  $sold = $sold + $row['BASE'];
  }
echo "<h2>You can take " . $sold . " safely</h2>";
$receipts = substr($receipts, 1, -1);
//echo '$receipts = '.$receipts."<br>";
//
$query = "DELETE FROM TAXLINES WHERE RECEIPT in ($receipts);";
$result = mysqli_query($con,$query);

$query = "DELETE FROM PAYMENTS WHERE RECEIPT in ($receipts);";
$result = mysqli_query($con,$query);

$query = "DELETE FROM TICKETLINES WHERE TICKET in ($receipts);";
$result = mysqli_query($con,$query);

$query = "delete from tickets where id in ($receipts);";
$result = mysqli_query($con,$query);


// Récupération de MONEY :
$MONEY = "";
$resultMoney = mysqli_query($con, "select MONEY from RECEIPTS where ID in ($receipts)");
while($rowMoney = @mysqli_fetch_array($resultMoney))
  {
	    $MONEY = $MONEY. ", '".$rowMoney['MONEY']."' ";
  }
$MONEY = substr($MONEY, 1, -1);
//echo '$MONEY = '.$MONEY."<br>";
//////////////////////////////////////////////////////////////////////////////////////


$query =  "DELETE FROM RECEIPTS WHERE ID in ($receipts)";
$result = mysqli_query($con,$query);


// Suppression de closecash correspondant :
$query = "delete from CLOSEDCASH where MONEY in ($MONEY)";
$ok = mysqli_query($con, $query);
//echo '$ok = '.$ok;
/////////////////////////////////////////////////////////

$query = "SET @line = 0"; 
mysqli_query($con,$query);

$query = "update tickets set TICKETID = @line := @line +1;";
mysqli_query($con,$query);

$query = "update ticketsnum set ID = (select max(ticketid) from tickets)";
mysqli_query($con,$query);

$query = "select PRODUCT, count(*) as UNITS from ticketlines group by product;";
$result = mysqli_query($con,$query);

while($row = mysqli_fetch_array($result))
  {
    $query = "UPDATE STOCKCURRENT SET UNITS = '-" . $row['UNITS']  . "' WHERE PRODUCT ='" .  $row['PRODUCT'] ."'; ";
    mysqli_query($con,$query);
  }

$query = "delete from stockdiary;";
$result = mysqli_query($con,$query);

//echo $tickets;
mysqli_close($con);
?>
