<?php

class MensagensController extends Controller
{
    public $use = ['Usuarios'];

    public function registrar($dados)
    {
        if (!empty($dados)) {
            $this->Mensagens->save($dados);
        } else {
            throw new Exception('Acesso indevido');
        }
    }

    public function sala($dados)
    {
        if (!empty($dados)) {
            $result = $this->Mensagens->sala($dados['chat_evento_id']);

            $helper = new Helper();
            echo $helper->json($result);
        } else {
            throw new Exception('Acesso indevido');
        }
    }

    public function teste()
    {
        $result = $this->Mensagens->sala(1);
        if (!empty($result)) {
            $helper = new Helper();
            echo $helper->json($result);
        }
    }
}
