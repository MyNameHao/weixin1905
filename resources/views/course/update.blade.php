<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<form action="{{url('course/updo/'.$openid)}}" method="post">
    <table >
        <tr>
            <td>第一节课</td>
            <td><select name="course_1" id="">
                    @foreach($data as $k=>$v)
                        @if($v->c_course==$courseinfo['course_1'])
                    <option value="{{$v->c_course}}" selected>{{$v->c_course}}</option>
                        @else
                            <option value="{{$v->c_course}}">{{$v->c_course}}</option>
                        @endif
                    @endforeach
                </select></td>
        </tr>
        <tr>
            <td>第二节课</td>
            <td><select name="course_2" id="">
                    @foreach($data as $k=>$v)
                        @if($v->c_course==$courseinfo['course_2'])
                        <option value="{{$v->c_course}}" selected>{{$v->c_course}}</option>
                        @else
                            <option value="{{$v->c_course}}">{{$v->c_course}}</option>
                        @endif
                    @endforeach
                </select></td>
        </tr>
        <tr>
            <td>第三节课</td>
            <td><select name="course_3" id="">
                    @foreach($data as $k=>$v)
                        @if($v->c_course==$courseinfo['course_3'])
                            <option value="{{$v->c_course}}" selected>{{$v->c_course}}</option>
                        @else
                            <option value="{{$v->c_course}}">{{$v->c_course}}</option>
                        @endif
                    @endforeach
                </select></td>
        </tr>
        <tr>
            <td>第四节课</td>
            <td><select name="course_4" id="">
                    @foreach($data as $k=>$v)
                        @if($v->c_course==$courseinfo['course_4'])
                            <option value="{{$v->c_course}}" selected>{{$v->c_course}}</option>
                        @else
                            <option value="{{$v->c_course}}">{{$v->c_course}}</option>
                        @endif
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