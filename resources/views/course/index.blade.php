<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    <table border="1">
        <tr>
            <td>第一节课</td>
            <td>{{$data['course_1']}}</td>
        </tr>
        <tr>
            <td>第二节课</td>
            <td>{{$data['course_2']}}</td>
        </tr>
        <tr>
            <td>第三节课</td>
            <td>{{$data['course_3']}}</td>
        </tr>
        <tr>
            <td>第四节课</td>
            <td>{{$data['course_4']}}</td>
        </tr>
        <tr>
            <td></td>
            <td><a href="{{url('course/update/'.$openid)}}">修改</a></td>
        </tr>
    </table>
</body>
</html>