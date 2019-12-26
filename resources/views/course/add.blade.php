<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<form action="{{url('course/create')}}" method="post">
    <table >
        <input type="hidden" name="openid" value="{{$openid}}">
        <tr>
            <td>第一节课</td>
            <td><select name="course_1" id="">
                    <option value=""></option>
                    @foreach($data as $k=>$v)
                    <option value="{{$v->c_course}}">{{$v->c_course}}</option>
                    @endforeach
                </select></td>
        </tr>
        <tr>
            <td>第二节课</td>
            <td><select name="course_2" id="">
                    <option value=""></option>
                    @foreach($data as $k=>$v)
                        <option value="{{$v->c_course}}">{{$v->c_course}}</option>
                    @endforeach
                </select></td>
        </tr>
        <tr>
            <td>第三节课</td>
            <td><select name="course_3" id="">
                    <option value=""></option>
                    @foreach($data as $k=>$v)
                        <option value="{{$v->c_course}}">{{$v->c_course}}</option>
                    @endforeach
                </select></td>
        </tr>
        <tr>
            <td>第四节课</td>
            <td><select name="course_4" id="">
                    <option value=""></option>
                @foreach($data as $k=>$v)
                        <option value="{{$v->c_course}}">{{$v->c_course}}</option>
                    @endforeach
                </select></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit"value="提交"></td>
        </tr>
    </table>
</form>
</body>
</html>