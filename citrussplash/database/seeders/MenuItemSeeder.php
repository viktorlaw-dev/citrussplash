<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    // All 86 items, transcribed 1:1 from the approved prototype
    // (design-reference/js/menu-data.js — itself transcribed from the
    // in-store menu photos). Prices are integers in naira, exactly as printed.
    //
    // Row shape: [slug, name, ingredients, price (₦), emoji, badge?]
    // NOTE: chicken-egg & chicken-avocado were partly glare-obscured on the
    // photo — entered as ₦5,600; the owner confirms both in admin later.
    public function run(): void
    {
        $smoothies = [
            ['cashew-nut', 'Cashew Nut Smoothie', 'Cashew nut, dates, watermelon.', 4550, '🥜'],
            ['keto', 'Keto', 'Watermelon, cabbage & almond nut.', 4900, '🥬'],
            ['bermuda-cruise', 'Bermuda Cruise', 'Golden melon, pineapple & apple.', 4650, '🍈'],
            ['african-paradise', 'African Paradise', 'Soursop, pineapple & apple.', 4650, '🍍'],
            ['paris-light', 'Paris Light', 'Avocado, ginger, pineapple & apple.', 4650, '🥑'],
            ['oat-smoothie', 'Oat Smoothie', 'Oat, pineapple, banana / berries.', 5000, '🥣'],
            ['aba-flavour', 'Aba Flavour', 'Pawpaw, watermelon, golden melon.', 4100, '🍈'],
            ['roman-splendor', 'Roman Splendor', 'Coconut, apple, pineapple.', 4650, '🥥'],
            ['barcelonia-drive', 'Barcelonia Drive', 'Pineapple, apple, carrot.', 4650, '🥕'],
            ['walnut', 'Walnut Smoothie', 'Walnut, date, watermelon, banana.', 4550, '🥜'],
            ['naija-flavor', 'Naija Flavor', 'Red grapes, pawpaw, orange.', 4550, '🍇'],
            ['abuja-tonic', 'Abuja Tonic', 'Watermelon, orange, mango. Our hometown hero.', 4550, '🍉', 'pop'],
            ['morning-boost', 'Morning Boost', 'Red grapes, mango, orange.', 4550, '🌅'],
            ['niger-delta', 'Niger Delta', 'Banana, apple, mango.', 4550, '🍌'],
            ['cuban-magic', 'Cuban Magic', 'Red grapes, apple, orange juice.', 4100, '🍇'],
            ['lagos-tropicana', 'Lagos Tropicana', 'Orange, banana, pineapple, mango.', 4650, '🏝️'],
            ['aruba-delish', 'Aruba Delish', 'Pineapple, apple, banana.', 4650, '🍍'],
            ['london-stress', 'London Stress Releaser', 'Red grapes, apple, pineapple.', 4900, '🍇'],
            ['caribbean-sunset', 'Caribbean Sunset', 'Coconut, pineapple, banana.', 4650, '🌅'],
            ['coconut-island', 'Coconut Island', 'Coconut, pineapple & white rum.', 4650, '🥥'],
            ['happy-me', 'Happy Me', 'Watermelon, banana, red grapes.', 4550, '😊'],
            ['beetroot-delight', 'Beetroot Delight', 'Beetroot, watermelon, banana.', 4100, '💜'],
            ['watermelon', 'Water Melon Smoothie', 'Pure blended watermelon. Simple & fresh.', 3450, '🍉', 'fresh'],
            ['glowing-skin', 'The Glowing Skin Smoothie', 'Pawpaw, date, carrot, orange.', 4550, '✨', 'new'],
            ['protein', 'Protein Smoothie', 'Peanut butter, banana, soya, milk.', 4550, '💪'],
            ['naughty-male', 'Naughty Me Male', 'Watermelon, almond nut, banana, date.', 5000, '🍉'],
            ['naughty-female', 'Naughty Me Female', 'Avocado, watermelon, almond nut, date.', 5000, '🥑'],
            ['florida-sunrise', 'Florida Sunrise', 'Apple, strawberry, pineapple.', 4900, '🌅'],
            ['atlanta-passion', 'Atlanta Passion', 'Orange, ginger, pineapple.', 4650, '🍊'],
            ['arsenal-delight', 'Arsenal Delight', 'Watermelon, pineapple, banana.', 4650, '🍉'],
            ['coffee-delight', 'Coffee Delight', 'Pineapple, coffee, apple.', 4650, '☕'],
            ['francisco-dream', 'Francisco Dream', 'Orange juice, banana, apple, kiwi.', 4100, '🥝'],
            ['tropical-paradise', 'Tropical Paradise', 'Watermelon, banana, orange.', 4100, '🏝️'],
            ['san-diego', 'San Diego Passion', 'Banana, guava, orange.', 4100, '🍊'],
            ['city-drive', 'City Drive', 'Watermelon, apple, banana.', 4100, '🍉'],
            ['bahamas', 'Summer in Bahamas', 'Cranberry, pineapple, apple.', 4900, '🏖️'],
            ['turkish-surprise', 'Turkish Surprise', 'Mango, kiwi, pineapple.', 4900, '🥭'],
            ['telaviv-heat', 'Telaviv Heat', 'Pineapple, guava, banana.', 4650, '🍍'],
            ['california-love', 'California Love', 'Mango, apple, coconut.', 4550, '🥭'],
            ['german-bazooka', 'German Bazooka', 'Red grapes, apple, mango, pineapple.', 4900, '🍇'],
            ['dubai-sunrise', 'Dubai Sunrise', 'Pineapple, apple, orange.', 4650, '🌅'],
            ['madrid-delight', 'Madrid Delight', 'Strawberry, pineapple, banana.', 4900, '🍓'],
            ['mexican-booster', 'Mexican Immunity Booster', 'Watermelon, pineapple, apple.', 4650, '🍉', 'new'],
            ['passion-delight', 'Passion Delight', 'Passion fruit, pineapple, banana.', 4650, '🍍'],
            ['green', 'Green Smoothie', 'Cucumber, pineapple, lettuce.', 4650, '🥒'],
            ['date', 'Date Smoothie', 'Date, watermelon, coconut.', 4100, '🌴'],
            ['orange-juice', 'Orange Juice', 'Freshly squeezed oranges. Nothing else.', 3450, '🍊', 'pop'],
            ['arabian-nights', 'Arabian Nights', 'Guava, banana, golden melon.', 4100, '🌙'],
            ['milan-wire', 'Milan Live Wire', 'Orange, pawpaw, banana.', 4100, '🍊'],
        ];

        $sandwiches = [
            ['club', 'Club Sandwich', 'Chicken, egg, cheese, tomatoes, lettuce, corned beef.', 5200, '🥪', 'pop'],
            ['sp-special', 'SP Special', 'Sausage, egg, chicken, cabbage, mayo.', 5200, '🥪', 'new'],
            ['chicken-sw', 'Chicken Sandwich', 'Chicken, lettuce, tomatoes, mayo.', 5200, '🥪'],
            ['chicken-egg', 'Chicken Egg Sandwich', 'Chicken, lettuce, tomatoes, egg, mayo.', 5600, '🍳'], // glare — confirm
            ['chicken-avocado', 'Chicken Avocado Sandwich', 'Chicken, lettuce, tomatoes, avocado, mayo.', 5600, '🥑'], // glare — confirm
            ['tuna-sw', 'Tuna Sandwich', 'Tuna, egg, tomatoes, lettuce, mayo.', 3450, '🐟'],
            ['sausage-sw', 'Sausage Sandwich', 'Sausage, cheese, mayo.', 3450, '🌭'],
            ['sausage-max', 'Sausage Max', 'Sausage, egg, cheese, mayo.', 3900, '🌭'],
            ['veggie-sw', 'Vegetable Sandwich', 'Lettuce, tomatoes, cucumber, egg, mayo.', 2750, '🥬', 'fresh'],
            ['saucho-delight', 'Saucho Delight', 'Sausage, cabbage, carrot, egg, lettuce.', 3450, '🌭'],
            ['avocado-sw', 'Avocado Sandwich', 'Avocado, egg, tomatoes, lettuce, mayo.', 3450, '🥑'],
        ];

        $salads = [
            ['chicken-salad', 'Chicken Salad', 'Fresh, crisp & delicious — made to order.', 9000, '🥗'],
            ['tuna-salad', 'Tuna Salad', 'Fresh, crisp & delicious — made to order.', 7000, '🥗'],
            ['sausage-salad', 'Sausage Salad', 'Fresh, crisp & delicious — made to order.', 7000, '🥗'],
            ['oatmeal', 'Oatmeal', 'Classic warm oatmeal, served fresh.', 4100, '🥣'],
        ];

        foreach (['smoothies' => $smoothies, 'sandwiches' => $sandwiches, 'salads' => $salads] as $catSlug => $items) {
            $category = Category::where('slug', $catSlug)->firstOrFail();

            foreach ($items as $i => $item) {
                MenuItem::updateOrCreate(['slug' => $item[0]], [
                    'category_id' => $category->id,
                    'name' => $item[1],
                    'description' => $item[2],
                    'price' => $item[3],
                    'emoji' => $item[4],
                    'tag' => $item[5] ?? null,
                    'is_available' => true,
                    'sort_order' => $i + 1, // position within the category, 1-based
                ]);
            }
        }
    }
}
