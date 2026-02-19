<?php
// Minimal standalone invoice content (used for download and included in show)
?>
<div style="font-family: Arial, Helvetica, sans-serif; max-width:800px;margin:20px auto;padding:20px;border:1px solid #ddd;">
    <header style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <div>
            <h2 style="margin:0;">Ma Boutique - Facture</h2>
            <div style="color:#666;font-size:14px;">www.ctf-php.com</div>
        </div>
        <div style="text-align:right;">
            <strong>Facture #<?php echo (int) $invoice['id']; ?></strong><br>
            <small><?php echo htmlspecialchars($invoice['date']); ?></small>
        </div>
    </header>

    <section style="margin-bottom:20px;">
        <strong>Facturé à :</strong>
        <div><?php echo htmlspecialchars($invoice['billing_address']); ?></div>
        <div><?php echo htmlspecialchars($invoice['billing_zip'] . ' ' . $invoice['billing_city']); ?></div>
    </section>

    <table style="width:100%;border-collapse:collapse;margin-bottom:20px;">
        <thead>
            <tr style="background:#f5f5f5;color:#111;text-align:left;">
                <th style="padding:8px;border:1px solid #e5e5e5;">Produit</th>
                <th style="padding:8px;border:1px solid #e5e5e5;">Quantité</th>
                <th style="padding:8px;border:1px solid #e5e5e5;">Prix unitaire</th>
                <th style="padding:8px;border:1px solid #e5e5e5;">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php $sum = 0; foreach ($items as $it): $line = $it['quantity'] * $it['unit_price']; $sum += $line; ?>
                <tr>
                    <td style="padding:8px;border:1px solid #e5e5e5;"><?php echo htmlspecialchars($it['challenge_title']); ?></td>
                    <td style="padding:8px;border:1px solid #e5e5e5;"><?php echo (int) $it['quantity']; ?></td>
                    <td style="padding:8px;border:1px solid #e5e5e5;"><?php echo number_format($it['unit_price'], 2, ',', ' '); ?> €</td>
                    <td style="padding:8px;border:1px solid #e5e5e5;"><?php echo number_format($line, 2, ',', ' '); ?> €</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="padding:8px;border:1px solid #e5e5e5;text-align:right;"><strong>Total</strong></td>
                <td style="padding:8px;border:1px solid #e5e5e5;"><strong><?php echo number_format($invoice['amount'], 2, ',', ' '); ?> €</strong></td>
            </tr>
        </tfoot>
    </table>

    <footer style="color:#777;font-size:13px;text-align:center;">Merci pour votre achat.</footer>
</div>
