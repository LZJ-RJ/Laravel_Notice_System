@extends('components.emailTemplate')

@section('email-content')
    <?php
        echo '<br>';
        echo $receiver_name;
        echo $content['content'];
        echo '<br>';
    ?>
@endsection
