<?php
 // T1 - TP2 - DSM3 2024.1 - Implementação e Refatoração com Padrões COMPOSITE PATTERN e FACTORY PATTERN
 // Script de Controle de Vendas de loja de produtos escolares com conceito POO, usando diagrama UML sem framework
 // Início do programa

// Comentario
// Classes

// Cliente
Class Cliente {
    Protected $nome;
    Protected $endereco;
    Protected $telefone;
    Protected $nascimento;
    Protected $status;
    Protected $email;
    Protected $genero;
    
    Private Static $contador = 0;
    Protected $idCliente;
    Protected $vendas;
    
    Function __construct($nome, $endereco, $telefone, $nascimento, $status, $email, $genero){
        $this->nome = $nome;
        $this->endereco = $endereco;
        $this->telefone = $telefone;
        $this->nascimento = $nascimento;
        $this->status = $status;
        $this->email = $email;
        $this->genero = $genero;
        
        self:$contador++;
        $this->idCliente = 'C' . self::$contador;
        $this->vendas= array();
    }

    Public Function dadosCliente(){
        echo "Id: ". $this->idCliente . "\n";
        echo "Nome: ". $this->nome . "\n";
        echo "Endereço: ". $this->endereco . "\n";
        echo "Telefone: ". $this->telefone . "\n";
        echo "Data de nascimento: ". $this->nascimento . "\n";
        echo "Status: ". $this->status . "\n";
        echo "Email: ". $this->email . "\n";
        echo "Gênero: ". $this->genero . "\n";
        echo "---\n";
    }

    Public Function getIdCliente(){
        Return $this->idCliente;
    }
}

// Venda
Class Venda {
    Protected $data;
    Protected $valorTot;
    Protected $itens;
    Protected $cliente;
    Protected $idCliente;
    Protected $idVenda;
    Private Static $contador = 0;
    
    Function __construct(Cliente $cliente, $idCliente){
        $this->idCliente = $idCliente;
        $this->data = date('Y-m-d H:i:s'); 
        $this->cliente = $cliente; 
        
        self:$contador++;
        $this->idVenda = 'PED' . self::$contador;
        $this->itens = array();
    }

    Public Function addItem(Produto $produto, $quantidade, $desconto){ 
        $item = New Item();
        $item->setProduto($produto);
        $item->setQuantidade($quantidade);
        $item->setDesconto($desconto);
        
        $item->setPreco($produto->getPreco());
        $item->totalItem();
        $this->itens[] = $item;
    }

    Public Function obterTotal(){
        $total = 0;
        foreach($this->itens as $item){
            $total += $item->getTotal();
        }
        
        $this->valorTot = $total;
        Return $total;
    }

    Public Function dadosVenda(){
        echo "Id do Pedido: ". $this->idVenda . "\n";
        echo "Data: ". $this->data . "\n";
        
        foreach($this->itens as $item) {
            echo "Produto: ". $item->getProduto()->getDescricao() . "\n";
            echo "Quantidade: ". $item->getQuantidade() . "\n";
            echo "Desconto: ". $item->getDesconto() . "\n";
            echo "Preço: ". $item->getPreco() . "\n";
            echo "Total do Item: R$". $item->getTotal() . "\n";
            echo "---\n";
        }
        
        echo "Total do Pedido: ". $this->valorTot . "\n";
        echo "------------------------------------\n";
    }

    Public Function decrementar(){
        self:$contador--; 
    }

    Public Function getCliente() {
        Return $this->cliente;
    }

    Public Function getIdVenda(){
        Return $this->idVenda;
    }

    Public Function getIdCliente(){
        Return $this->idCliente;
    }
}

// Item
Class Item{
    Protected $preco;
    Protected $quantidade;
    Protected $desconto;
    Protected $total;
    Protected $produto;
    
    Public Function totalItem(){
       $this->total = $this->quantidade * $this->preco * (1 - $this->desconto);
    }

    Public Function setProduto(Produto $produto){
        $this->produto = $produto;
    }

    Public Function setPreco($preco) {
        $this->preco = $preco;
    }

    Public Function setQuantidade($quantidade) {
        $this->quantidade = $quantidade;
    }

    Public Function setDesconto($desconto) {
        $this->desconto = $desconto;
    }

    Public Function getProduto(){
        Return $this->produto;
    }

    Public Function getQuantidade(){
        Return $this->quantidade;
    }

    Public Function getDesconto(){
        Return $this->desconto;
    }

    Public Function getPreco(){
        Return $this->preco;
    }
    
    public function getTotal(){
        return $this->total;
    }
}

// Produto
class Produto{
    protected $descricao;
    protected $estoque;
    protected $preco;
    protected $medida;
    
    function __construct ($descricao, $estoque, $preco, $medida){
        $this->descricao = $descricao;
        $this->estoque = $estoque;
        $this->preco = $preco;
        $this->medida = $medida;
    }
    
    public function dadosProduto(){
        echo "Descrição: " . $this->descricao . "\n";
        echo "Estoque: " . $this->estoque . "\n";
        echo "Preço: " . $this->preco . "\n";
        echo "Unidade de medida: " . $this->medida . "\n";
        echo "---\n";
    }
    
    public function getDescricao(){
        return $this->descricao; 
    }
    
    public function getPreco(){
        return $this->preco;
    }
}

// Interfaces e Factories

// Interface para Factory de Cliente
interface ClienteFactoryInterface {
    public function criarCliente($nome, $endereco, $telefone, $nascimento, $status, $email, $genero);
}

// Factory concreto para Cliente
class ClienteFactory implements ClienteFactoryInterface {
    public function criarCliente($nome, $endereco, $telefone, $nascimento, $status, $email, $genero) {
        return new Cliente($nome, $endereco, $telefone, $nascimento, $status, $email, $genero);
    }
}

// Interface para Factory de Produto
interface ProdutoFactoryInterface {
    public function criarProduto($descricao, $estoque, $preco, $medida);
}

// Factory concreto para Produto
class ProdutoFactory implements ProdutoFactoryInterface {
    public function criarProduto($descricao, $estoque, $preco, $medida) {
        return new Produto($descricao, $estoque, $preco, $medida);
    }
}

// Programa Principal

$clientesCad = array();
$produtosCad = array();
$vendasCad = array();

$clienteFactory = new ClienteFactory();
$produtoFactory = new ProdutoFactory();

do {
    // Menu e lógica do programa
    echo "------------------------------------\n";
    echo "1- Cadastrar Produto\n";
    echo "2- Listar Produtos\n";
    echo "3- Cadastrar Cliente\n";
    echo "4- Listar Clientes\n";
    echo "5- Cadastrar Venda\n";
    echo "6- Listar Vendas\n";
    echo "7- Imprimir pedido\n";
    echo "0- Sair\n";
    echo "------------------------------------\n";
    
    $menu = trim(fgets(STDIN));
    
    switch ($menu) {
        case 1:
            // Cadastrar Produto
            echo "------------------------------------\n";
            $descricao = readline("Descrição do produto: ");
            $estoque = readline("Estoque: ");
            $preco = readline("Preço: ");
            $medida = readline("Unidade de medida: ");
            echo "------------------------------------\n";
            
            // Criar objeto Produto usando a factory
            $produto = $produtoFactory->criarProduto($descricao, $estoque, $preco, $medida);
            
            // Guardar produto no array
            $produtosCad[] = $produto;
            break;
        
        case 2: 
            // Listar Produtos
            if (!empty($produtosCad)) {
                echo "------------------------------------\n";
                echo "PRODUTOS CADASTRADOS: \n";
                
                foreach ($produtosCad as $itemProduto) {
                    $itemProduto->dadosProduto();
                }
            } else {
                echo "------------------------------------\n";
                echo "NENHUM PRODUTO CADASTRADO! \n";
            }
            break;

        case 3:
            // Cadastrar Cliente
            echo "------------------------------------\n";
            $nome = readline("Nome: ");
            $endereco = readline("Endereço: ");
            $telefone = readline("Telefone [11 123456789]: ");
            $nascimento = readline("Data de nascimento [dd-mm-aaaa]: ");
            $status = readline("Status [ativo]/[inativo]: ");
            $email = readline("Email: ");
            $genero = readline("Gênero [f]/[m]: ");
            echo "------------------------------------\n";
            
            // Criar objeto Cliente usando a factory
            $cliente = $clienteFactory->criarCliente($nome, $endereco, $telefone, $nascimento, $status, $email, $genero);
            
            // Guardar cliente no array
            $clientesCad[] = $cliente;
            break;

        case 4:
            // Listar Clientes
            if(!empty($clientesCad)){
                echo "------------------------------------\n";
                echo "CLIENTES CADASTRADOS: \n";
                
                foreach ($clientesCad as $cliente){
                    $cliente->dadosCliente();
                }
            } else {
                echo "------------------------------------\n";
                echo "NENHUM CLIENTE CADASTRADO! \n";
            }
            break;

        case 5:
            // Cadastrar Venda
            echo "------------------------------------\n";
            $idCliente = readline("Id do cliente: ");
            
            // Verificar se o cliente existe
            $clienteEncontrado = false;
            foreach ($clientesCad as $clienteDisponivel){
                if ($clienteDisponivel->getIdCliente() === $idCliente) {
                    $clienteEncontrado = true;
                    break;
                }
            }
            
            if (!$clienteEncontrado) {
                echo "Cliente não cadastrado!\n";
            } else {
                // Criar objeto Venda
                $venda = new Venda($clienteDisponivel, $idCliente);
                
                // Adicionar itens à venda
                do {
                    $produtoEncontrado = false;
                    
                    $descricao = readline("Descrição do produto: ");
                    
                    foreach ($produtosCad as $produtoDisponivel){
                        if ($produtoDisponivel->getDescricao() == $descricao){
                            $produtoEncontrado = true;
                            
                            $quantidade = readline("Quantidade: ");
                            $desconto = readline("Desconto [0.1 = 10%]: ");
                            
                            $venda->addItem($produtoDisponivel, $quantidade, $desconto);
                        }
                    }
                    
                    if (!$produtoEncontrado) {
                        echo "Produto não cadastrado!\n";
                    }
                    
                    echo "------------------------------------\n";
                    echo "1- Adicionar outro item \n";
                    echo "2- Finalizar Pedido \n";
                    echo "0- Cancelar Venda \n";
                    $m = trim(fgets(STDIN));
                    
                    if ($m == 2){
                        $valorTot = $venda->obterTotal();
                        echo "Total da venda: R$" . $valorTot . "\n";
                        
                        $vendasCad[] = $venda;
                    }
                    
                    if ($m == 0) {
                        echo "Venda cancelada!\n";
                        $venda->decrementar();
                        unset($venda);
                        break;
                    }
                    
                } while ($m != 0 && $m != 2);
            }
            break;

        case 6:
            // Listar Vendas
            if(!empty($vendasCad)){
                echo "------------------------------------\n";
                $idCliente = readline("Id do cliente: \n");
                echo "\n VENDAS REGISTRADAS:\n";
                echo "------------------------------------\n";
                
                foreach ($vendasCad as $venda){
                    if ($venda->getCliente()->getIdCliente() === $idCliente) {
                        $venda->dadosVenda();
                    }
                }
            } else {
                echo "------------------------------------\n";
                echo "NENHUMA VENDA REGISTRADA! \n";
            }
            break;

            case 7:
                
                //solicita o código do Pedido
                echo "------------------------------------\n";
                $idVenda = readline("Id do Pedido: \n");
                
                //localiza o pedido
                foreach ($vendasCad as $venda){
                    if ($venda->getIdVenda() === $idVenda){
                        
                        echo "--------IMPRESSÃO DO PEDIDO--------\n";
                        //exibe os dados da venda 
                        $venda->dadosVenda();
                        
                        //localiza o cliente do pedido e exibi os dados
                        foreach ($clientesCad as $cliente){
                            if ($cliente->getIdCliente() === $venda->getIdCliente()){
                                $cliente->dadosCliente();
                            }
                        }
                        
                    }
                }
                
                
                
                break;

        case 0:
            echo "Encerrando o programa...\n";
            break;

        default:
            echo "Entrada inválida!\n";
            break;
    }
    
} while ($menu != 0);

?>
