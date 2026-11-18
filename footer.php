<footer class="ifro-footer">
    <svg class="ifro-waves" viewBox="0 24 150 28" preserveAspectRatio="none" aria-hidden="true">
        <defs>
            <path id="ifro-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z"/>
        </defs>
        <g class="ifro-parallax">
            <use href="#ifro-wave" x="48" y="0" fill="rgba(42,127,212,0.35)"/>
            <use href="#ifro-wave" x="48" y="3" fill="rgba(42,127,212,0.55)"/>
            <use href="#ifro-wave" x="48" y="5" fill="rgba(42,127,212,0.8)"/>
            <use href="#ifro-wave" x="48" y="7" fill="#2a7fd4"/>
        </g>
    </svg>

    <div class="ifro-body">
        <span class="ifro-bubble" style="--x:8%;  --s:10px; --d:9s;  --delay:0s"></span>
        <span class="ifro-bubble" style="--x:22%; --s:16px; --d:12s; --delay:2s"></span>
        <span class="ifro-bubble" style="--x:41%; --s:8px;  --d:8s;  --delay:4s"></span>
        <span class="ifro-bubble" style="--x:63%; --s:14px; --d:11s; --delay:1s"></span>
        <span class="ifro-bubble" style="--x:78%; --s:9px;  --d:10s; --delay:3s"></span>
        <span class="ifro-bubble" style="--x:92%; --s:18px; --d:13s; --delay:5s"></span>

        <div class="container ifro-content">
            <div class="row gy-4">
                <div class="col-md-4">
                    <h5>🌊 Ifrocean</h5>
                    <p>Recenser et quantifier les populations animales de l’estran des grandes plages de sable de la côte atlantique.</p>
                </div>
                <div class="col-md-4">
                    <h5>Zone contact</h5>
                    <ul class="list-unstyled">
                        <li>📧 contact@ifrocean.fr</li>
                        <li>📞 01 23 45 67 89</li>
                        <li>📍 Côte atlantique, France</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Navigation</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php">Accueil</a></li>
                        <li><a href="#">Zone scientifique</a></li>
                        <li><a href="#">Synthèse étude</a></li>
                    </ul>
                </div>
            </div>
            <div class="ifro-copy">© <?= date('Y') ?> Ifrocean — Tous droits réservés</div>
        </div>
    </div>

    <style>
        .ifro-footer { margin-top: 0; color: #fff; }

        .ifro-waves { display: block; width: 100%; height: 70px; margin-bottom: -2px; }
        .ifro-parallax > use { animation: ifro-move 25s cubic-bezier(.55,.5,.45,.5) infinite; }
        .ifro-parallax > use:nth-child(1) { animation-delay: -2s; animation-duration: 7s; }
        .ifro-parallax > use:nth-child(2) { animation-delay: -3s; animation-duration: 10s; }
        .ifro-parallax > use:nth-child(3) { animation-delay: -4s; animation-duration: 13s; }
        .ifro-parallax > use:nth-child(4) { animation-delay: -5s; animation-duration: 20s; }
        @keyframes ifro-move {
            0%   { transform: translate3d(-90px, 0, 0); }
            100% { transform: translate3d(85px, 0, 0); }
        }

        .ifro-body {
            position: relative;
            overflow: hidden;
            background: linear-gradient(180deg, #2a7fd4 0%, #0b3a78 55%, #06214a 100%);
        }
        .ifro-content { position: relative; z-index: 2; padding: 10px 15px 20px; }
        .ifro-footer h5 { font-weight: 700; margin-bottom: 12px; }
        .ifro-footer p, .ifro-footer li { margin-bottom: 6px; color: rgba(255,255,255,.92); }
        .ifro-footer a { color: #bfe3ff; text-decoration: none; }
        .ifro-footer a:hover { color: #fff; text-decoration: underline; }
        .ifro-copy {
            margin-top: 25px; padding-top: 14px;
            border-top: 1px solid rgba(255,255,255,.2);
            text-align: center; font-size: .9rem; opacity: .85;
        }

        /* Bulles */
        .ifro-bubble {
            position: absolute; bottom: -30px; left: var(--x);
            width: var(--s); height: var(--s);
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, rgba(255,255,255,.6), rgba(255,255,255,.1));
            border: 1px solid rgba(255,255,255,.35);
            animation: ifro-rise var(--d) ease-in infinite;
            animation-delay: var(--delay);
            z-index: 1;
        }
        @keyframes ifro-rise {
            0%   { transform: translateY(0) translateX(0); opacity: 0; }
            15%  { opacity: 1; }
            100% { transform: translateY(-260px) translateX(20px); opacity: 0; }
        }

        @media (max-width: 768px) { .ifro-waves { height: 40px; } }
        @media (prefers-reduced-motion: reduce) {
            .ifro-parallax > use, .ifro-bubble { animation: none; }
        }
    </style>
</footer>