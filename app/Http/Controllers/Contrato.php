<?php

namespace App\Http\Controllers;

use App\Models\Locacao;
use Illuminate\Http\Request;
Use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class Contrato extends Controller
{
    public function printLocacao($id)
    {
        //FORMATAR DATA
        $locacao = Locacao::find($id);
        Carbon::setLocale('pt-BR');
        $dataAtual = Carbon::now();




        //FORMATAR CPF
         $CPF_LENGTH = 11;
         $cnpj_cpf = preg_replace("/\D/", '', $locacao->Cliente->cpf_cnpj);

        if (strlen($cnpj_cpf) === $CPF_LENGTH) {
                $cpfCnpj = preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
        }
        else {
            $cpfCnpj =  preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
        }

        //FORMATAR TELEFONE
         $tel_1 = $locacao->Cliente->telefone_1;
         $tel_2 = $locacao->Cliente->telefone_2;
         $locacao->valor_desconto = number_format($locacao->valor_desconto,  2, ",", ".");
         $locacao->valor_total_desconto = number_format($locacao->valor_total_desconto, 2, ",", ".");




         return pdf::loadView('pdf.locacao.contrato', compact(['locacao',
                                                        'dataAtual',
                                                        'cpfCnpj',
                                                        'tel_1',
                                                        'tel_2']))
                                                        ->stream();


    }
}
