<!-- redirecting the user to the shortened url -->
<?php 
    include "php/config.php";
    $new_url = "";
    if(isset($_GET)){
        foreach($_GET as $key=>$val){
        $u = mysqli_real_escape_string($conn, $key);
        $new_url = str_replace('/', '', $u);
        }
        $sql = mysqli_query($conn, "SELECT full_url FROM url WHERE shorten_url = '{$new_url}'");
        if(mysqli_num_rows($sql) > 0){
            $sql2 = mysqli_query($conn, "UPDATE url SET clicks = clicks + 1 WHERE shorten_url = '{$new_url}'");
            if($sql2){
                $full_url = mysqli_fetch_assoc($sql);
                header("Location:".$full_url['full_url']);
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v3.0.6/css/line.css">
    <link rel="stylesheet" href="style.css">
    <title>Tiny Traverse</title>
</head>
<body>
    <div class="wrapper">
        <form action="#" autocomplete="off">
            <input type="text" spellcheck="false" name="full-url" placeholder="Enter or paste a long url" required>
            <i class="url-icon uil uil-link"></i>
            <button>Shorten</button>
        </form>
        <?php
            $sql2 = mysqli_query($conn, "SELECT * FROM url ORDER BY id DESC");
            if(mysqli_num_rows($sql2) > 0) {
            ?>
                <div class="count">
                    <?php
                        $sql3 = mysqli_query($conn, "SELECT COUNT(*) FROM url");
                        $res = mysqli_fetch_assoc($sql3);

                        $sql4 = mysqli_query($conn, "SELECT clicks FROM url");
                        $total = 0;
                        while($count = mysqli_fetch_assoc($sql4)){
                            $total = $count['clicks'] + $total;
                        }
                    ?>
                    <span>Total Links: 
                        <span><?php echo end($res) ?></span> & Total Clicks:
                        <span><?php echo $total ?></span>
                    </span>
                    <a href="php/delete.php?delete=all">Clear All</a>
                </div>
                <div class="urls-area">
                    <div class="title">
                        <li>Shorten URL</li>
                        <li>Original URL</li>
                        <li>Clicks</li>
                        <li>Action</li>
                    </div>
                <?php
                while($row = mysqli_fetch_assoc($sql2)) {
                    ?>
                        <div class="data">
                            <li>
                                <a href="http://localhost/url/<?php echo $row['shorten_url'] ?>" target="_blank">
                                    <?php 
                                        if('localhost/url/' . $row['shorten_url'] > 50) {
                                            echo 'localhost/url/'.substr($row['shorten_url'], 0, 50);
                                        } else {
                                            echo 'localhost/url/'.$row['shorten_url'];
                                        }
                                    ?>
                                </a>
                            </li>
                            <li>
                                <?php 
                                    if(strlen($row['full_url']) > 60) {
                                        echo substr($row['full_url'], 0, 60).'...';
                                    } else {
                                        echo $row['full_url'];
                                    }
                                ?>
                            </li>
                            <li><?php echo $row['clicks']?></li>
                            <li><a href="php/delete.php?id=<?php echo $row['shorten_url'] ?>">Delete</a></li>
                        </div>
                    <?php
                }
            ?>
        </div>
            <?php
        }
        ?>
    <footer style="text-align: center; margin-top: 10px; color: grey; font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;">
        <p>💪Powered by Tiny Traverse</p>
    </footer>
    </div>
    <div class="blur-effect"></div>
    <div class="popup-box">
        <div class="info-box">Your short link is ready. You can also edit your short link now but can't edit once you saved it.</div>
        <form action="#">
            <label>Edit your shortened url</label>
            <input type="text" spellcheck="false" value="">
            <i class="copy-icon uil uil-copy-alt"></i>
            <button>Save</button>
        </form>
    </div>
        

    <script src="script.js"></script>
</body>
</html>

