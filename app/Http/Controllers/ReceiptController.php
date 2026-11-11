<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class ReceiptController extends Controller
{
    public function printReceipt()
    {
        try {
            $connector = new WindowsPrintConnector("POS-58");

            $printer = new Printer($connector);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setEmphasis(true);
            $printer->text("TOKO CONTOH\n");
            $printer->setEmphasis(false);

            $printer->setJustification(Printer::JUSTIFY_LEFT);

            $printer->text("TOKO CONTOH\n");
            $printer->text("Jl. Mawar No. 123\n");
            $printer->text("========================\n");
            $printer->text("Barang A       2 x 5.000\n");
            $printer->text("Barang B       1 x 10.000\n");
            $printer->text("------------------------\n");
            $printer->text("Total:         Rp20.000\n");
            $printer->text("========================\n");
            $printer->text("Terima kasih!\n");

            $printer->cut();
            $printer->close();

            return response()->json([
                'message' => 'Struk berhasil dicetak'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}