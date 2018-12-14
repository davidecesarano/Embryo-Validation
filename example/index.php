<?php 
    
    require __DIR__.'/../vendor/autoload.php';
    
    use Embryo\Http\Factory\ServerRequestFactory;
    use Embryo\Validation\Validation;

    $request    = (new ServerRequestFactory)->createServerRequestFromServer();
    $validation = new Validation($request);

?>

    <!doctype html>
    <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
            <title>Hello World!</title>
        </head>
        <body>
            
            <div class="container">
                <?php if (empty($request->getParsedBody())): ?>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Name (required)</label>
                            <input type="text" name="name" class="form-control" placeholder="Name">
                        </div>
                        <div class="form-group">
                            <label>Email (required)</label>
                            <input type="email" name="email" class="form-control" placeholder="name@example.com">
                        </div>
                        <div class="form-group">
                            <label>Date (required)</label>
                            <input type="date" name="date" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>File (required)</label>
                            <input type="file" class="form-control-file" name="file">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                <?php else: ?>
                    <?php 
                        $validation->name('name')->type('text')->required();
                        $validation->name('email')->type('email')->required();
                        $validation->name('date')->type('datetime')->required();
                        $validation->name('file')->type('file')->accept('image/png')->required();

                        if ($validation->isSuccess()) {
                            $data = $validation->getData();
                            echo '<h1>Success!</h1>';
                            echo '<ul>';
                                echo '<li>Name: '.$data['name'].'</li>';
                                echo '<li>Email: '.$data['email'].'</li>';
                                echo '<li>Date: '.$data['date'].'</li>';
                                echo '<li>File: '.$data['file']->getClientFileName().'</li>';
                            echo '</ul>';
                        } else {
                            echo '<h1>Error!</h1>';
                            echo '<ul>';
                                foreach ($validation->getErrorList() as $error) {
                                    echo '<li>'.$error.'</li>';
                                }
                            echo '<ul>';
                        }
                    ?>
                <?php endif; ?>
            </div>

            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        </body>
    </html>
