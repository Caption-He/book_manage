<?php
	header("Content-Type:text/html;charset=utf-8");
	session_start();
	$searchs = $_POST['search'];
	$name = $classname = $account = "";
	$a=0;
	if (isset($_SESSION['nickname']))
	{
		$a = 1;
		if ($_SESSION['class'])
		{
			$classname = "图书管理员";
			$a = 2;
		}
		else
			$classname = "普通用户";
		$name = $_SESSION['nickname'];
		$account = $_SESSION['account'];
	}
	
?>
<html>
<head>
	<title>郑州大学图书管理系统</title>
	<meta charset="UTF-8"/>
	<link rel="stylesheet" type="text/css" href="backstage/CSS.css">

</head>
<?php
	
	include "backstage/page.cless.php";
	include("config.php");
	
	$sum="SELECT count(*) FROM borrow WHERE user='".$account."'";
	$summ=mysql_query($sum,$online);
	$summm=mysql_fetch_array($summ);
	$tota = $summm['0'];
	$jii="SELECT jibie FROM user WHERE account='".$account."'";
	$jiii=mysql_query($jii,$online);
	$jiiii=mysql_fetch_array($jiii);
	$k= $jiiii[0];
	$shen='本科生';$t=5;
	
	if($k=="a")
	{
		$shen='教师';
		$t=8;
	}

	if($k=="b")
	{
		$shen='研究生';
		$t=7;
	}

	$outcome= mysql_query("SELECT * FROM book_message where book_title like \"%$searchs%\"",$online);

	echo'<table align = "center" border = "1" width = "960" style="text-align: center;">';
	echo "<caption><h1>郑州大学图书借阅系统</h1></caption>";
	echo "<caption>"."<h5>"."注*："."$shen"."最多借"."$t"."本，您已经借了"."$tota"."本了"."</h5>";
	
	echo"</caption>";
	echo "<tr>";
	echo "<td>"."书名"."</td>";
	echo "<td>"."作者"."</td>";
	echo "<td>"."入库时间"."</td>";
	echo "<td>"."类型"."</td>";
	echo "<td>"."单价"."</td>";
	echo "<td>"."剩余数量"."</td>";
	echo "<td>"."操作"."</td>";
	echo "</tr>";
	
	while($sql = mysql_fetch_array($outcome))
	{
		
		echo "<tr>";
		$id = $sql['num'];
		echo "<td>".$sql['book_title']."</td>";
		echo "<td>".$sql['author']."</td>";
		echo "<td>".$sql['add_time']."</td>";
		echo "<td>".$sql['type']."</td>";
		echo "<td>".$sql['money']."元</td>";
		echo "<td>".$sql['number']."</td>";
		$zj="SELECT * FROM borrow WHERE book_id='".$id."'";
		$zjsz=mysql_query($zj,$online);
		$var=0;
		
		while($arr = mysql_fetch_array($zjsz))
		{ 
			if(($arr["book_id"]==$id)&&($arr["user"]==$account))
			{
				
				echo "<td class='color' >&nbsp;已借&nbsp;</td>";
				$var++;
				break;

			}

		}
		if(($var==0)&&($sql['number']==0))
			echo "<td>本图书已借完</td>";
		else
			if($var==0)
			{
				if($tota<5)
					echo "<td>"."<a class=\"borrow\" href="."\""."borrow.php?id=".$id."&& book_title=".$sql['book_title']."\"".">&nbsp;借书&nbsp;</a></td>";
				else
					echo "<td class='color' >&nbsp;超额&nbsp;</td>";

			}
			
		echo "</tr>";

	}
	echo "<tr><td colspan = \"9\" align = \"center\"><a href=\"index.php\">返回图书借阅界面</a></td></tr>";
	echo "</table>";
	mysql_close($online);

?>
</body>
</html>