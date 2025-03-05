<?php
    use App\Utils\LocationUtils;
    use App\Utils\MessageUtil;

?>

                </div>
            </main>
            <?php include  __DIR__."/footers/footer.php" ?>
        </div>
    </div>

    <script src="<?=LocationUtils::assetFor('template/js/app.js')?>"></script>
    <script>

        <?php
            $message = MessageUtil::getMessage();

            if ($message != null) {
                echo "alert('$message')";
            }
        ?>
    </script>
</body>
</html>

