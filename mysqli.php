<?php
header('Content-Type:text/html; charset=utf-8');
//利用msyqli连接mysql数据库
$mysqli = new mysqli('localhost','root','baixiaoshi2@lion','test','3306');
//测试mysqli是否连接mysql数据库成功
if($mysqli->connect_errno)
{
	echo 'mysqli连接失败';
	return;
}
//采用预处理的方式来插入数据库
$sql = "insert into t2(name,age) values(?,?)";
//预处理sql语句
$mysqli_stmt = $mysqli->prepare($sql);
//绑定参数
$name="hello world";
$age = 23;
$mysqli_stmt->bind_param('sd',$name,$age);
//执行sql语句
$result = $mysqli_stmt->execute();
if($result)
{
	echo "执行成功";
}
else
{
	echo '执行失败';
}

/*//测试query语句
$sql = "select id,name,age from t1 where id=1;";
$sql .= "select id,name,age from t1 where id=2;";
$sql .= "select id,name,age from t1 where id=3";
//这里获取的是结果集，其实就是一个二维表
//这里执行多行语句
if ($mysqli->multi_query($sql)) {
    do {

        if ($result = $mysqli->store_result()) {
            while ($row = $result->fetch_row()) {
                printf("%s\n", $row[0]);
            }
            $result->free();
        }

        if ($mysqli->more_results()) {
            printf("-----------------\n");
        }
    } while ($mysqli->next_result());
}


//关闭连接
$mysqli->close();*/
