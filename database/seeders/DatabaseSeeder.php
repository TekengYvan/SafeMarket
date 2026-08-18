<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\Negotiation;
use App\Models\Notification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            CategorySeederV2::class,
        ]);

        // ─── 1. Admin Account ───────────────────────────────────────────────
        $admin = User::updateOrCreate(
            ['email' => 'admin@safemarket.cm'],
            [
                'name'         => 'Admin SafeMarket',
                'password'     => Hash::make('password'),
                'is_admin'     => true,
                'balance'      => 45000.00,
                'kyc_status'   => 'verified',
                'trust_score'  => 100,
                'phone_number' => '+237690000001',
            ]
        );
        $admin->syncRoles(['admin']);

        // ─── 2. Vendor Account ──────────────────────────────────────────────
        $vendor = User::updateOrCreate(
            ['email' => 'vendor@safemarket.cm'],
            [
                'name'         => 'Tech & Fashion Store',
                'password'     => Hash::make('password'),
                'is_admin'     => false,
                'balance'      => 350000.00,
                'kyc_status'   => 'verified',
                'trust_score'  => 98,
                'phone_number' => '+237677123456',
            ]
        );
        $vendor->syncRoles(['vendor']);

        // ─── 3. Buyer Account ───────────────────────────────────────────────
        $buyer = User::updateOrCreate(
            ['email' => 'buyer@safemarket.cm'],
            [
                'name'         => 'Christian Ngana',
                'password'     => Hash::make('password'),
                'is_admin'     => false,
                'balance'      => 850000.00,
                'kyc_status'   => 'verified',
                'trust_score'  => 95,
                'phone_number' => '+237699887766',
            ]
        );
        $buyer->syncRoles(['buyer']);

        // ─── 4. Resolve categories safely ───────────────────────────────────
        $electronique = Category::where('slug', 'electronique')->first()
            ?? Category::where('name', 'like', '%lectronique%')->first()
            ?? Category::first();

        $mode = Category::where('slug', 'mode-vetements')->first()
            ?? Category::where('name', 'like', '%Mode%')->first()
            ?? $electronique;

        $smartphones = Category::updateOrCreate(
            ['slug' => 'smartphones'],
            ['name' => 'Smartphones', 'parent_id' => optional($electronique)->id]
        );

        $ordinateurs = Category::updateOrCreate(
            ['slug' => 'ordinateurs'],
            ['name' => 'Ordinateurs & Laptops', 'parent_id' => optional($electronique)->id]
        );

        $chaussures = Category::updateOrCreate(
            ['slug' => 'chaussures'],
            ['name' => 'Chaussures & Sneakers', 'parent_id' => optional($mode)->id]
        );

        $sacs = Category::updateOrCreate(
            ['slug' => 'sacs-accessoires'],
            ['name' => 'Sacs & Bagages', 'parent_id' => optional($mode)->id]
        );

        // ─── 5. Seed Realistic Products ─────────────────────────────────────
        $p1 = Product::updateOrCreate(
            ['title' => 'iPhone 13 Pro Max (128 Go) - État Impeccable'],
            [
                'vendor_id'   => $vendor->id,
                'category_id' => $smartphones->id,
                'description' => "iPhone 13 Pro Max d'origine en excellent état, capacité de batterie 92%. Toujours protégé par une vitre blindée et coque MagSafe. Vendu avec boîte originale et câble de recharge rapide.",
                'price'       => 450000.00,
                'condition'   => 'like_new',
                'status'      => 'available',
                'location'    => 'Douala (Akwa)',
                'is_in_stock' => true,
            ]
        );

        $p2 = Product::updateOrCreate(
            ['title' => 'MacBook Pro 13" M1 (8 Go RAM, 256 Go SSD)'],
            [
                'vendor_id'   => $vendor->id,
                'category_id' => $ordinateurs->id,
                'description' => "MacBook Pro avec puce Apple M1 haute performance. Idéal pour les développeurs, créateurs de contenu ou étudiants. Autonomie exceptionnelle de plus de 15h, écran Retina True Tone impeccable.",
                'price'       => 650000.00,
                'condition'   => 'good',
                'status'      => 'available',
                'location'    => 'Yaoundé (Bastos)',
                'is_in_stock' => true,
            ]
        );

        $p3 = Product::updateOrCreate(
            ['title' => 'Nike Air Force 1 All White (Pointure 42)'],
            [
                'vendor_id'   => $vendor->id,
                'category_id' => $chaussures->id,
                'description' => "Baskets originales Nike Air Force 1 blanches. Neuves avec étiquettes dans leur emballage d'origine. Cuir de haute qualité, confort inégalé.",
                'price'       => 45000.00,
                'condition'   => 'new',
                'status'      => 'available',
                'location'    => 'Douala (Bonapriso)',
                'is_in_stock' => true,
            ]
        );

        $p4 = Product::updateOrCreate(
            ['title' => 'Sac à dos imperméable antivol avec port USB'],
            [
                'vendor_id'   => $vendor->id,
                'category_id' => $sacs->id,
                'description' => "Sac à dos haut de gamme ergonomique, matière étanche résistant à l'eau et aux rayures. Compartiment rembourré pour PC jusqu'à 15.6 pouces, fermeture éclair cachée antivol et port de charge USB extérieur.",
                'price'       => 20000.00,
                'condition'   => 'new',
                'status'      => 'available',
                'location'    => 'Yaoundé (Omnisports)',
                'is_in_stock' => true,
            ]
        );

        $p5 = Product::updateOrCreate(
            ['title' => 'Samsung Galaxy S23 Ultra (256 Go) - Neuf sous blister'],
            [
                'vendor_id'   => $vendor->id,
                'category_id' => $smartphones->id,
                'description' => 'Samsung Galaxy S23 Ultra neuf et non déballé, 256 Go de stockage, stylet S-Pen inclus. Double SIM, appareil photo 200 MP, écran Dynamic AMOLED 2X. Garantie constructeur active.',
                'price'       => 520000.00,
                'condition'   => 'new',
                'status'      => 'available',
                'location'    => 'Douala (Bonanjo)',
                'is_in_stock' => true,
            ]
        );

        // ─── 6. Seed a Price Negotiation ────────────────────────────────────
        $neg = Negotiation::updateOrCreate(
            ['product_id' => $p1->id, 'buyer_id' => $buyer->id],
            [
                'seller_id'      => $vendor->id,
                'original_price' => $p1->price,
                'proposed_price' => 420000.00,
                'status'         => 'accepted',
                'message'        => "Bonjour ! Je suis intéressé par votre iPhone 13 Pro Max. Acceptez-vous une offre à 420.000 FCFA cash ?",
            ]
        );

        if ($neg->messages()->count() === 0) {
            $neg->messages()->create(['user_id' => $buyer->id,  'content' => "Bonjour ! Je suis intéressé par votre iPhone 13 Pro Max. Acceptez-vous une offre à 420.000 FCFA cash ?"]);
            $neg->messages()->create(['user_id' => $vendor->id, 'content' => "Bonjour Christian. D'accord pour 420.000 FCFA si la transaction est validée aujourd'hui via l'Escrow SafeMarket !"]);
        }

        // ─── 7. Seed an Escrow Order ────────────────────────────────────────
        Order::updateOrCreate(
            ['buyer_id' => $buyer->id, 'product_id' => $p3->id],
            [
                'amount'          => 45000.00,
                'phone'           => '+237699887766',
                'location'        => 'Douala, Akwa Nord (Face Supermarché Mahima)',
                'status'          => 'shipped',
                'tracking_number' => 'SM-CAM-889412',
                'release_code'    => 'SAFE8892',
            ]
        );

        // ─── 8. Notifications ───────────────────────────────────────────────
        Notification::updateOrCreate(
            ['user_id' => $buyer->id, 'title' => 'Commande expédiée 📦'],
            [
                'content' => "Le vendeur Tech & Fashion Store a expédié votre commande pour 'Nike Air Force 1 All White'. Numéro de suivi : SM-CAM-889412.",
                'is_read' => false,
            ]
        );

        Notification::updateOrCreate(
            ['user_id' => $vendor->id, 'title' => 'Offre de prix acceptée 🤝'],
            [
                'content' => "Vous avez accepté l'offre de Christian Ngana pour 'iPhone 13 Pro Max (128 Go)' à 420 000 FCFA.",
                'is_read' => true,
            ]
        );

        if ($this->command) {
            $this->command->info('✅ Base de données peuplée avec succès !');
            $this->command->info('   admin@safemarket.cm  / password  (Admin)');
            $this->command->info('   vendor@safemarket.cm / password  (Vendeur)');
            $this->command->info('   buyer@safemarket.cm  / password  (Acheteur — 850 000 FCFA de solde)');
        }
    }
}

