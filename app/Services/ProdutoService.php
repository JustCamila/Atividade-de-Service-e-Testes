<?php

namespace App\Services;

use App\Models\Produto;
use Illuminate\Validation\ValidationException;

class ProdutoService
{
    private const MARGEM_MINIMA = 0.10;

    public function create(array $dados): Produto
    {
        $this->verificarMargem($dados['preco'], $dados['preco_custo']);

        return Produto::create($dados);
    }

    // 1) Método UPDATE adicionado
    public function update(int $id, array $dados): Produto
    {
        $produto = Produto::findOrFail($id);

        $preco = $dados['preco'] ?? $produto->preco;
        $precoCusto = $dados['preco_custo'] ?? $produto->preco_custo;

        $this->verificarMargem($preco, $precoCusto);

        $produto->update($dados);

        return $produto;
    }

    private function verificarMargem(float $preco, float $precoCusto): void
    {
        // Preço mínimo deve ser o custo + 10%
        $margem = $precoCusto + ($precoCusto * self::MARGEM_MINIMA);
        $minimo = round($margem, 2);

        if ($preco < $minimo) {
            throw ValidationException::withMessages([
                'preco' => sprintf('O preço do produto deve ser no minimo %.2f (Margem Minima)', $minimo),
            ]);
        }
    }
}