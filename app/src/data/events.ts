export interface EventData {
    id: number;
    name: string;
    date: string;
    location: string;
    description: string;
    price: string;
    image: string;
    status: 'upcoming' | 'sold-out' | 'past';
    lineup: string[];
    media: { url: string, type: 'photo' | 'video' }[];
}

export const events: EventData[] = [
    {
        id: 1,
        name: 'Forró de Responsa',
        date: '14 de Outubro, 2026',
        location: 'Arena Ktorze, Lauro de Freitas',
        description: 'O icônico evento de forró tradicional onde tudo começou. Junte-se a nós para uma noite de muita dança, música boa e energia inesquecível.',
        price: 'R$ 60,00',
        image: 'https://images.unsplash.com/photo-1533174000276-8090333ccdf8?q=80&w=2938&auto=format&fit=crop',
        status: 'upcoming',
        lineup: ['Dorgival Dantas', 'Falamansa', 'Léo Estakazero', 'Targino Gondim'],
        media: [
            { url: 'https://images.unsplash.com/photo-1533174000276-8090333ccdf8?q=80&w=2938&auto=format&fit=crop', type: 'photo' },
            { url: 'https://images.unsplash.com/photo-1470229722913-7c092fb1f144?q=80&w=2942&auto=format&fit=crop', type: 'video' },
            { url: 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=2874&auto=format&fit=crop', type: 'photo' }
        ]
    },
    {
        id: 2,
        name: 'Summer Vibes Festival',
        date: '05 de Dezembro, 2026',
        location: 'Praia de Ipitanga',
        description: 'Um grande festival de música à beira-mar com os melhores artistas de música pop e eletrônica da temporada.',
        price: 'R$ 120,00',
        image: 'https://images.unsplash.com/photo-1459749411175-04bf5292ceea?q=80&w=2940&auto=format&fit=crop',
        status: 'upcoming',
        lineup: ['Alok', 'Vintage Culture', 'Dubdogz', 'Cat Dealers'],
        media: [
            { url: 'https://images.unsplash.com/photo-1459749411175-04bf5292ceea?q=80&w=2940&auto=format&fit=crop', type: 'photo' },
            { url: 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?q=80&w=2940&auto=format&fit=crop', type: 'video' },
            { url: 'https://images.unsplash.com/photo-1506157786151-b8491531f063?q=80&w=2940&auto=format&fit=crop', type: 'photo' }
        ]
    },
    {
        id: 3,
        name: 'Reveillon KTORZE',
        date: '31 de Dezembro, 2026',
        location: 'Vilas do Atlântico',
        description: 'A melhor festa de virada de ano do litoral norte, com open bar premium, queima de fogos exclusiva e atrações surpresa até o amanhecer.',
        price: 'R$ 350,00',
        image: 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=2940&auto=format&fit=crop',
        status: 'upcoming',
        lineup: ['Ivete Sangalo', 'Léo Santana', 'KVSH', 'Xand Avião'],
        media: [
            { url: 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=2940&auto=format&fit=crop', type: 'photo' },
            { url: 'https://images.unsplash.com/photo-1543807535-eceef0bc6599?q=80&w=2940&auto=format&fit=crop', type: 'video' },
            { url: 'https://images.unsplash.com/photo-1524368535928-5b5e00ddc76b?q=80&w=2940&auto=format&fit=crop', type: 'photo' }
        ]
    },
    {
        id: 4,
        name: 'Bloco KTORZE M no Carnaval',
        date: '15 de Fevereiro, 2027',
        location: 'Circuito Barra-Ondina',
        description: 'Nossa estreia no maior carnaval do planeta! Um bloco exclusivo com muito conforto, segurança e a energia inigualável da KTORZE M.',
        price: 'R$ 250,00',
        image: 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=2874&auto=format&fit=crop',
        status: 'upcoming',
        lineup: ['Bell Marques', 'Durval Lelys', 'Banda Eva', 'Timbalada'],
        media: [
            { url: 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=2874&auto=format&fit=crop', type: 'photo' },
            { url: 'https://images.unsplash.com/photo-1540039155732-68c8c08b541e?q=80&w=3267&auto=format&fit=crop', type: 'video' },
            { url: 'https://images.unsplash.com/photo-1429962714451-bb934ecdc4ec?q=80&w=2940&auto=format&fit=crop', type: 'photo' }
        ]
    }
];
