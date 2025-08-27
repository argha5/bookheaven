<?php
// Get audiobook details if ID is provided
$audiobookId = $_GET['id'] ?? '';
$selectedAudiobook = null;

if ($audiobookId) {
    $stmt = $pdo->prepare("SELECT * FROM audiobooks WHERE audiobook_id = ? AND status = 'visible'");
    $stmt->execute([$audiobookId]);
    $selectedAudiobook = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<?php if ($selectedAudiobook): ?>
    <!-- Audiobook Player -->
    <div class="row">
        <div class="col-md-4">
            <img src="<?= htmlspecialchars($selectedAudiobook['poster_url'] ?? 'https://via.placeholder.com/400x400/9b59b6/ffffff?text=Audiobook') ?>" 
                 class="img-fluid rounded shadow" alt="<?= htmlspecialchars($selectedAudiobook['title']) ?>">
        </div>
        <div class="col-md-8">
            <h2><?= htmlspecialchars($selectedAudiobook['title']) ?></h2>
            <p class="text-muted mb-2">
                <i class="fas fa-user me-2"></i>By <?= htmlspecialchars($selectedAudiobook['writer']) ?>
            </p>
            <p class="text-muted mb-2">
                <i class="fas fa-tags me-2"></i><?= htmlspecialchars($selectedAudiobook['genre']) ?>
            </p>
            <p class="text-muted mb-3">
                <i class="fas fa-clock me-2"></i><?= htmlspecialchars($selectedAudiobook['duration']) ?> minutes
            </p>
            
            <?php if (!empty($selectedAudiobook['description'])): ?>
                <p><?= htmlspecialchars($selectedAudiobook['description']) ?></p>
            <?php endif; ?>
            
            <!-- Audio Player -->
            <div class="card mt-4">
                <div class="card-body">
                    <h5><i class="fas fa-play-circle me-2"></i>Audio Player</h5>
                    <?php if (!empty($selectedAudiobook['audio_url'])): ?>
                        <audio controls class="w-100">
                            <source src="<?= htmlspecialchars($selectedAudiobook['audio_url']) ?>" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>Audio file not available
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="mt-3">
                <a href="/app.php?page=audiobooks" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Audiobooks
                </a>
            </div>
        </div>
    </div>
<?php else: ?>
    <!-- Audiobooks List -->
    <div class="row">
        <div class="col-12">
            <h2><i class="fas fa-headphones me-2"></i>Audiobooks Collection</h2>
            <p class="text-muted mb-4">Listen to your favorite books on the go</p>
        </div>
    </div>
    
    <div class="row">
        <?php if (empty($audiobooks)): ?>
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-headphones fa-3x text-muted mb-3"></i>
                        <h4>No audiobooks available</h4>
                        <p class="text-muted">Check back later for new audiobook releases</p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <?php foreach($audiobooks as $audiobook): ?>
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card book-card">
                        <div class="position-relative">
                            <img src="<?= htmlspecialchars($audiobook['poster_url'] ?? 'https://via.placeholder.com/300x400/9b59b6/ffffff?text=Audiobook') ?>" 
                                 class="card-img-top book-cover" alt="<?= htmlspecialchars($audiobook['title']) ?>">
                            <div class="position-absolute top-50 start-50 translate-middle">
                                <i class="fas fa-play-circle fa-3x text-white opacity-75"></i>
                            </div>
                            <span class="badge bg-info position-absolute top-0 end-0 m-2">
                                <i class="fas fa-headphones me-1"></i>Audio
                            </span>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title fw-bold"><?= htmlspecialchars($audiobook['title']) ?></h6>
                            <p class="text-muted small mb-1">
                                <i class="fas fa-user me-1"></i><?= htmlspecialchars($audiobook['writer']) ?>
                            </p>
                            <p class="text-muted small mb-1">
                                <i class="fas fa-tags me-1"></i><?= htmlspecialchars($audiobook['genre']) ?>
                            </p>
                            <p class="text-muted small mb-3">
                                <i class="fas fa-clock me-1"></i><?= htmlspecialchars($audiobook['duration']) ?> mins
                            </p>
                            
                            <?php if (!empty($audiobook['description'])): ?>
                                <p class="small text-muted mb-3">
                                    <?= htmlspecialchars(substr($audiobook['description'], 0, 100)) ?>...
                                </p>
                            <?php endif; ?>
                            
                            <div class="d-grid">
                                <a href="/app.php?page=audiobooks&id=<?= $audiobook['audiobook_id'] ?>" class="btn btn-info">
                                    <i class="fas fa-play me-2"></i>Listen Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
<?php endif; ?>
