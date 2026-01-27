<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buku Besar</title>
    <link rel="stylesheet" href="<?=base_url()?>assets/css/new-report.css">
</head>
<body>

<div class="container">
    <h1>HIGADGET</h1>
    <h2>LAPORAN BUKU BESAR</h2>
    <h3>Tanggal <?= date_format(date_create($date_start), 'd M Y') ?> s.d <?= date_format(date_create($date_end), 'd M Y') ?></h3>
    <p class="subtitle">(dalam rupiah)</p>

    <?php 
        $saldo = $saldo_awal;
        if ($saldo_awal != 0) { 
    ?>
    <table class="neraca">
        <thead>
            <tr>
                <th colspan="6" style="text-align:left;">BUKU KAS</th>
            </tr>
            <tr>
                <th>TGL</th>
                <th>BANK</th>
                <th>KETERANGAN</th>
                <th>DEBET</th>
                <th>KREDIT</th>
                <th>SALDO</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $date_start ?></td>
                <td></td>
                <td>Kas pembukaan</td>
                <td class="amount"><?php echo number_format($saldo_awal, 0, ',', '.') ?></td>
                <td class="amount"></td>
                <td class="amount"><?php echo number_format($saldo, 0, ',', '.') ?></td>
            </tr>
        </tbody>
    </table>

    <br>

    <?php } ?>

    <table class="neraca">
        <thead>
            <tr>
                <th colspan="6" style="text-align:left;">PENJUALAN</th>
            </tr>
            <tr>
                <th>TGL</th>
                <th>BANK</th>
                <th>KETERANGAN</th>
                <th>DEBET</th>
                <th>KREDIT</th>
                <th>SALDO</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $total_debet = 0;
                foreach ($data_penjualan as $penjualan) { 
                $debet = $penjualan->total_penjualan;
                $total_debet += $debet;
                $saldo += $debet;
            ?>
                <tr>
                    <td><?php echo $penjualan->tanggal ?></td>
                    <td></td>
                    <td><?php echo "Total penjualan tanggal ".$penjualan->tanggal ?></td>
                    <td class="amount"><?php echo number_format($debet, 0, ',', '.') ?></td>
                    <td class="amount"></td>
                    <td class="amount"><?php echo number_format($saldo, 0, ',', '.') ?></td>
                </tr>

                <?php 
                    if ($penjualan->total_diskon + $penjualan->total_cashback >= 0) {
                        $diskon = $penjualan->total_diskon + $penjualan->total_cashback;
                        $saldo -= $diskon;
                ?>
                <tr>
                    <td><?php echo $penjualan->tanggal ?></td>
                    <td></td>
                    <td><?php echo "Diskon/Cashback penjualan tanggal ".$penjualan->tanggal ?></td>
                    <td class="amount"></td>
                    <td class="amount"><?php echo number_format($diskon, 0, ',', '.') ?></td>
                    <td class="amount"><?php echo number_format($saldo, 0, ',', '.') ?></td>
                </tr>
            <?php 
                    }
            } ?>

            <tr class="total">
                <td colspan="3">TOTAL</td>
                <td class="amount"><?php echo number_format($total_debet, 0, ',', '.') ?></td>
                <td class="amount"></td>
                <td class="amount"><?php echo number_format($saldo, 0, ',', '.') ?></td>
            </tr>
        </tbody>
    </table>

    <br>

    <?php 
    foreach ($data_pemasukan_per_kategori as $kategori => $data_pemasukan) { ?>
    <table class="neraca">
        <thead>
            <tr>
                <th colspan="6" style="text-align:left;"><?php echo $kategori ?></th>
            </tr>
            <tr>
                <th>TGL</th>
                <th>BANK</th>
                <th>KETERANGAN</th>
                <th>DEBET</th>
                <th>KREDIT</th>
                <th>SALDO</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $total_debet = 0;
                foreach ($data_pemasukan as $pemasukan) { 
                $debet = $pemasukan->nominal;
                $total_debet += $debet;
                $saldo += $debet;
            ?>
                <tr>
                    <td><?php echo $pemasukan->tgl_transaksi ?></td>
                    <td></td>
                    <td><?php echo $pemasukan->catatan ?></td>
                    <td class="amount"><?php echo number_format($debet, 0, ',', '.') ?></td>
                    <td class="amount"></td>
                    <td class="amount"><?php echo number_format($saldo, 0, ',', '.') ?></td>
                </tr>
            <?php } ?>

            <tr class="total">
                <td colspan="3">TOTAL</td>
                <td class="amount"><?php echo number_format($total_debet, 0, ',', '.') ?></td>
                <td class="amount"></td>
                <td class="amount"><?php echo number_format($saldo, 0, ',', '.') ?></td>
            </tr>
        </tbody>
    </table>

    <br>

    <?php } ?>

    <table class="neraca">
        <thead>
            <tr>
                <th colspan="6" style="text-align:left;">PEMBELIAN</th>
            </tr>
            <tr>
                <th>TGL</th>
                <th>BANK</th>
                <th>KETERANGAN</th>
                <th>DEBET</th>
                <th>KREDIT</th>
                <th>SALDO</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $total_kredit = 0;
                foreach ($data_pembelian as $pembelian) { 
                $kredit = $pembelian->total_hpp;
                $total_kredit += $kredit;
                $saldo -= $kredit;
            ?>
                <tr>
                    <td><?php echo $pembelian->tanggal ?></td>
                    <td></td>
                    <td><?php echo "Total pembelian tanggal ".$pembelian->tanggal ?></td>
                    <td class="amount"></td>
                    <td class="amount"><?php echo number_format($kredit, 0, ',', '.') ?></td>
                    <td class="amount"><?php echo number_format($saldo, 0, ',', '.') ?></td>
                </tr>
            <?php } ?>

            <tr class="total">
                <td colspan="3">TOTAL</td>
                <td class="amount"><?php echo number_format(0, 0, ',', '.') ?></td>
                <td class="amount"><?php echo number_format($total_kredit, 0, ',', '.') ?></td>
                <td class="amount"><?php echo number_format($saldo, 0, ',', '.') ?></td>
            </tr>
        </tbody>
    </table>

    <br>

    <?php 
    foreach ($data_pengeluaran_per_kategori as $kategori => $data_pengeluaran) { ?>
    <table class="neraca">
        <thead>
            <tr>
                <th colspan="6" style="text-align:left;"><?php echo $kategori ?></th>
            </tr>
            <tr>
                <th>TGL</th>
                <th>BANK</th>
                <th>KETERANGAN</th>
                <th>DEBET</th>
                <th>KREDIT</th>
                <th>SALDO</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $total_kredit = 0;
                foreach ($data_pengeluaran as $pengeluaran) { 
                $kredit = $pengeluaran->nominal;
                $total_kredit += $kredit;
                $saldo -= $kredit;
            ?>
                <tr>
                    <td><?php echo $pengeluaran->tgl_transaksi ?></td>
                    <td></td>
                    <td><?php echo $pengeluaran->catatan ?></td>
                    <td class="amount"></td>
                    <td class="amount"><?php echo number_format($kredit, 0, ',', '.') ?></td>
                    <td class="amount"><?php echo number_format($saldo, 0, ',', '.') ?></td>
                </tr>
            <?php } ?>

            <tr class="total">
                <td colspan="3">TOTAL</td>
                <td class="amount"></td>
                <td class="amount"><?php echo number_format($total_kredit, 0, ',', '.') ?></td>
                <td class="amount"><?php echo number_format($saldo, 0, ',', '.') ?></td>
            </tr>
        </tbody>
    </table>

    <br>

    <?php } ?>

    <?php 
        $saldo -= $saldo_akhir;
        if ($saldo_akhir != 0) { 
    ?>
    <table class="neraca">
        <thead>
            <tr>
                <th colspan="6" style="text-align:left;">BUKU KAS</th>
            </tr>
            <tr>
                <th>TGL</th>
                <th>BANK</th>
                <th>KETERANGAN</th>
                <th>DEBET</th>
                <th>KREDIT</th>
                <th>SALDO</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $date_end ?></td>
                <td></td>
                <td>Kas penutupan</td>
                <td class="amount"></td>
                <td class="amount"><?php echo number_format($saldo_akhir, 0, ',', '.') ?></td>
                <td class="amount"><?php echo number_format($saldo, 0, ',', '.') ?></td>
            </tr>
        </tbody>
    </table>

    <br>

    <?php } ?>

</div>

</body>
</html>
