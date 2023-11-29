<?php
// Get the 4 most recently added products
$stmt = $pdo->prepare('SELECT * FROM produtos ORDER BY date_added DESC LIMIT 4');
$stmt->execute();
$recently_added_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?=template_header('Início')?>

<div class="featured">
    <h2>Cupcakes Danchou</h2>
    <p>Uma delícia de sabores</p>
</div>
<div class="recentlyadded content-wrapper">
    <h2>Últimos produtos adicionados</h2>
    <div class="products">
        <?php foreach ($recently_added_products as $product): ?>
        <a href="index.php?page=product&id=<?=$product['id']?>" class="product">
            <img src="<?=$product['imagem_url']?>" width="250" height="250" alt="<?=$product['nome_produto']?>">
            <span class="name"><?=$product['nome_produto']?></span>
            <span class="price">
                R&dollar;<?=$product['preco_produto']?>
                <?php if (isset($product['rrp']) && $product['rrp'] > 0): ?>
                <span class="rrp">R&dollar;<?=$product['rrp']?></span>
                <?php endif; ?>
            </span>
        </a>
        <?php endforeach; ?>
    </div>
</div>

<?=template_footer()?>