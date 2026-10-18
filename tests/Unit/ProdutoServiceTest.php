<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ProdutoService;
use App\Models\Produto;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProdutoServiceTest extends TestCase
{
    use RefreshDatabase;

    private ProdutoService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ProdutoService();
    }

    public function test_nao_cria_produto_se_preco_for_menor_que_margem_minima()
    {
        $this->expectException(ValidationException::class);

    
        $dados = [
            'nome' => 'Mouse Gamer',
            'preco_custo' => 100.00,
            'preco' => 105.00,
        ];

        $this->service->create($dados);
    }

    public function test_atualiza_produto_com_sucesso()
    {
        $produto = Produto::create([
            'nome' => 'Teclado',
            'preco_custo' => 100.00,
            'preco' => 120.00,
        ]);

        $dadosAtualizados = [
            'nome' => 'Teclado Mecânico',
            'preco' => 150.00,
        ];

        $resultado = $this->service->update($produto->id, $dadosAtualizados);

        $this->assertEquals('Teclado Mecânico', $resultado->nome);
        $this->assertEquals(150.00, $resultado->preco);
    }

    public function test_nao_atualiza_produto_se_novo_preco_for_menor_que_margem_minima()
    {
        $this->expectException(ValidationException::class);

        $produto = Produto::create([
            'nome' => 'Monitor',
            'preco_custo' => 500.00,
            'preco' => 600.00,
        ]);

        $dadosInvalidos = [
            'preco' => 520.00,
        ];

        $this->service->update($produto->id, $dadosInvalidos);
    }
}