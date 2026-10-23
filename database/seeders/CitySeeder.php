<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;
use Illuminate\Support\Str;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            // Major Egyptian Cities
            'Cairo', 'Alexandria', 'Giza', 'Sharm El Sheikh', 'Hurghada', 'Luxor', 'Aswan', 'Dahab', 'Marsa Alam', 'Siwa',
            'Port Said', 'Suez', 'Ismailia', 'Mansoura', 'Tanta', 'Zagazig', 'Beni Suef', 'Minya', 'Assiut', 'Sohag',
            'Qena', 'Luxor', 'Aswan', 'Kom Ombo', 'Edfu', 'Abu Simbel', 'El Gouna', 'Makadi Bay', 'Sahl Hasheesh', 'Safaga',
            'El Quseir', 'Marsa Alam', 'Berenice', 'Halayeb', 'Shalateen', 'Abu Ramad', 'Ras Gharib', 'El Tor', 'Saint Catherine', 'Dahab',
            'Nuweiba', 'Taba', 'Ras Abu Galum', 'Blue Hole', 'Colored Canyon', 'Wadi Rum', 'Petra', 'Aqaba', 'Eilat', 'Taba Heights',
            
            // Major International Cities (for international trips)
            'Dubai', 'Abu Dhabi', 'Doha', 'Kuwait City', 'Riyadh', 'Jeddah', 'Mecca', 'Medina', 'Amman', 'Beirut',
            'Istanbul', 'Ankara', 'Antalya', 'Cappadocia', 'Pamukkale', 'Ephesus', 'Bodrum', 'Fethiye', 'Marmaris', 'Kusadasi',
            'Athens', 'Thessaloniki', 'Santorini', 'Mykonos', 'Rhodes', 'Crete', 'Corfu', 'Zakynthos', 'Kos', 'Paros',
            'Rome', 'Milan', 'Venice', 'Florence', 'Naples', 'Pisa', 'Turin', 'Bologna', 'Verona', 'Genoa',
            'Paris', 'Lyon', 'Marseille', 'Nice', 'Toulouse', 'Bordeaux', 'Strasbourg', 'Nantes', 'Montpellier', 'Lille',
            'London', 'Manchester', 'Birmingham', 'Liverpool', 'Edinburgh', 'Glasgow', 'Bristol', 'Leeds', 'Sheffield', 'Newcastle',
            'Madrid', 'Barcelona', 'Valencia', 'Seville', 'Bilbao', 'Malaga', 'Granada', 'Cordoba', 'Toledo', 'Salamanca',
            'Berlin', 'Munich', 'Hamburg', 'Frankfurt', 'Cologne', 'Stuttgart', 'Düsseldorf', 'Dortmund', 'Essen', 'Leipzig',
            'Amsterdam', 'Rotterdam', 'The Hague', 'Utrecht', 'Eindhoven', 'Tilburg', 'Groningen', 'Almere', 'Breda', 'Nijmegen',
            'Brussels', 'Antwerp', 'Ghent', 'Bruges', 'Charleroi', 'Liège', 'Namur', 'Mons', 'Leuven', 'Mechelen',
            'Vienna', 'Graz', 'Linz', 'Salzburg', 'Innsbruck', 'Klagenfurt', 'Villach', 'Wels', 'Sankt Pölten', 'Dornbirn',
            'Prague', 'Brno', 'Ostrava', 'Plzen', 'Liberec', 'Olomouc', 'Ústí nad Labem', 'České Budějovice', 'Hradec Králové', 'Pardubice',
            'Budapest', 'Debrecen', 'Szeged', 'Miskolc', 'Pécs', 'Győr', 'Nyíregyháza', 'Kecskemét', 'Székesfehérvár', 'Szombathely',
            'Warsaw', 'Kraków', 'Łódź', 'Wrocław', 'Poznań', 'Gdańsk', 'Szczecin', 'Bydgoszcz', 'Lublin', 'Katowice',
            'Moscow', 'Saint Petersburg', 'Novosibirsk', 'Yekaterinburg', 'Kazan', 'Nizhny Novgorod', 'Chelyabinsk', 'Samara', 'Omsk', 'Rostov',
            'Istanbul', 'Ankara', 'Izmir', 'Bursa', 'Antalya', 'Adana', 'Gaziantep', 'Konya', 'Mersin', 'Diyarbakır',
            'Tehran', 'Mashhad', 'Isfahan', 'Tabriz', 'Shiraz', 'Kerman', 'Yazd', 'Qom', 'Kermanshah', 'Urmia',
            'Baghdad', 'Basra', 'Mosul', 'Erbil', 'Sulaymaniyah', 'Najaf', 'Karbala', 'Kirkuk', 'Ramadi', 'Fallujah',
            'Damascus', 'Aleppo', 'Homs', 'Hama', 'Latakia', 'Tartus', 'Daraa', 'Idlib', 'Deir ez-Zor', 'Al-Hasakah',
            'Beirut', 'Tripoli', 'Sidon', 'Tyre', 'Zahle', 'Baalbek', 'Jounieh', 'Byblos', 'Batroun', 'Jbeil',
            'Amman', 'Zarqa', 'Irbid', 'Al-Salt', 'Al-Karak', 'Madaba', 'Jerash', 'Al-Mafraq', 'Aqaba', 'Al-Tafilah',
            'Jerusalem', 'Tel Aviv', 'Haifa', 'Rishon LeZion', 'Petah Tikva', 'Ashdod', 'Netanya', 'Beer Sheva', 'Holon', 'Bnei Brak',
            'Riyadh', 'Jeddah', 'Mecca', 'Medina', 'Dammam', 'Khobar', 'Dhahran', 'Tabuk', 'Abha', 'Jizan',
            'Doha', 'Al Wakrah', 'Al Khor', 'Lusail', 'Al Rayyan', 'Umm Salal', 'Al Daayen', 'Al Shamal', 'Al Gharafa', 'Al Sadd',
            'Kuwait City', 'Salmiya', 'Hawally', 'Jahra', 'Farwaniya', 'Mubarak Al-Kabeer', 'Ahmadi', 'Al Jahra', 'Al Wafra', 'Al Khiran',
            'Abu Dhabi', 'Al Ain', 'Dubai', 'Sharjah', 'Ajman', 'Umm Al Quwain', 'Ras Al Khaimah', 'Fujairah', 'Al Dhafra', 'Al Gharbia',
            'Muscat', 'Salalah', 'Sohar', 'Nizwa', 'Sur', 'Ibri', 'Saham', 'Barka', 'Rustaq', 'Al Buraimi',
            'Sanaa', 'Aden', 'Taiz', 'Hodeidah', 'Ibb', 'Dhamar', 'Al Mukalla', 'Zabid', 'Saada', 'Marib',
            'Addis Ababa', 'Dire Dawa', 'Mekelle', 'Gondar', 'Bahir Dar', 'Jimma', 'Dessie', 'Hawassa', 'Jijiga', 'Shashamane',
            'Nairobi', 'Mombasa', 'Kisumu', 'Nakuru', 'Eldoret', 'Nyeri', 'Thika', 'Malindi', 'Kitale', 'Kakamega',
            'Dar es Salaam', 'Mwanza', 'Arusha', 'Dodoma', 'Mbeya', 'Morogoro', 'Tanga', 'Kahama', 'Tabora', 'Zanzibar',
            'Kampala', 'Gulu', 'Lira', 'Mbarara', 'Jinja', 'Mbale', 'Arua', 'Masaka', 'Tororo', 'Entebbe',
            'Khartoum', 'Omdurman', 'Port Sudan', 'Kassala', 'El Obeid', 'Nyala', 'Wad Madani', 'El Fasher', 'Kosti', 'Gedaref',
            'Tripoli', 'Benghazi', 'Misrata', 'Tobruk', 'Sabha', 'Zliten', 'Ajdabiya', 'Al Bayda', 'Derna', 'Tobruk',
            'Tunis', 'Sfax', 'Sousse', 'Ettadhamen', 'Kairouan', 'Gabès', 'Ariana', 'Gafsa', 'Monastir', 'La Marsa',
            'Algiers', 'Oran', 'Constantine', 'Annaba', 'Batna', 'Blida', 'Setif', 'Chlef', 'Djelfa', 'Sétif',
            'Rabat', 'Casablanca', 'Fez', 'Marrakech', 'Agadir', 'Tangier', 'Meknes', 'Oujda', 'Kénitra', 'Tetouan',
            'Cairo', 'Alexandria', 'Giza', 'Sharm El Sheikh', 'Hurghada', 'Luxor', 'Aswan', 'Dahab', 'Marsa Alam', 'Siwa'
        ];

        $this->command->info('Creating cities...');
        
        foreach ($cities as $cityName) {
            City::firstOrCreate(
                ['name' => $cityName],
                [
                    'name' => $cityName,
                    'slug' => Str::slug($cityName),
                ]
            );
        }

        $this->command->info('Cities created successfully!');
        $this->command->info('Total cities: ' . City::count());
    }
} 