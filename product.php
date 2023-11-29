<?php
// Check to make sure the id parameter is specified in the URL
if (isset($_GET['id'])) {
    // Prepare statement and execute, prevents SQL injection
    $stmt = $pdo->prepare('SELECT * FROM produtos WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    // Fetch the product from the database and return the result as an Array
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    // Check if the product exists (array is not empty)
    if (!$product) {
        // Simple error to display if the id for the product doesn't exists (array is empty)
        exit('Produto não existe!');
    }
} else {
    // Simple error to display if the id wasn't specified
    exit('Produto não existe');
}
?>

<?=template_header('Produto')?>

<div class="product content-wrapper">
    <img src="<?=$product['imagem_url']?>" width="500" height="500" alt="<?=$product['nome_produto']?>">
    <div>
        <h1 class="name"><?=$product['nome_produto']?></h1>
        <span class="price">
                R&dollar;<?=$product['preco_produto']?>
                <?php if (isset($product['rrp']) && $product['rrp'] > 0): ?>
                <span class="rrp">R&dollar;<?=$product['rrp']?></span>
            <?php endif; ?>
        </span>
        <form action="index.php?page=cart" method="post">
            <input type="number" name="quantity" value="1" min="1" max="<?=$product['quantity']?>" placeholder="Quantity" required>
            <input type="hidden" name="product_id" value="<?=$product['id']?>">
            <input type="submit" value="ADICIONAR AO CARRINHO">
        </form>
        <div class="description">
            <?=nl2br($product['descricao_produto'])?>
        </div>
    </div>
</div>

<?=template_footer()?>