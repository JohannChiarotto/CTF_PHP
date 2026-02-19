<?php
// views/invoice/show.php - invoice preview inside layout
?>
<main class="container" style="padding:40px 0;">
    <div style="max-width:900px;margin:0 auto;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;">
            <h1>Facture #<?php echo (int) $invoice['id']; ?></h1>
            <div>
                <a href="<?php echo $baseUrl; ?>/invoice?id=<?php echo (int) $invoice['id']; ?>&download=1" class="btn-start">Télécharger</a>
            </div>
        </div>

        <?php include __DIR__ . '/file.php'; ?>
    </div>
</main>
