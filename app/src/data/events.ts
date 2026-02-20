export interface EventData {
    id: number;
    name: string;
    date: string;
    location: string;
    description: string;
    price: string;
    image: string;
    status: 'upcoming' | 'sold-out' | 'past';
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
        status: 'upcoming'
    },
    {
        id: 2,
        name: 'Summer Vibes Festival',
        date: '05 de Dezembro, 2026',
        location: 'Praia de Ipitanga',
        description: 'Um grande festival de música à beira-mar com os melhores artistas de música pop e eletrônica da temporada.',
        price: 'R$ 120,00',
        image: 'https://images.unsplash.com/photo-1459749411175-04bf5292ceea?q=80&w=2940&auto=format&fit=crop',
        status: 'upcoming'
    },
    {
        id: 3,
        name: 'Reveillon KTORZE',
        date: '31 de Dezembro, 2026',
        location: 'Vilas do Atlântico',
        description: 'A melhor festa de virada de ano do litoral norte, com open bar premium, queima de fogos exclusiva e atrações surpresa até o amanhecer.',
        price: 'R$ 350,00',
        image: 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=2940&auto=format&fit=crop',
        status: 'upcoming'
    },
    {
        id: 4,
        name: 'Bloco KTORZE M no Carnaval',
        date: '15 de Fevereiro, 2027',
        location: 'Circuito Barra-Ondina',
        description: 'Nossa estreia no maior carnaval do planeta! Um bloco exclusivo com muito conforto, segurança e a energia inigualável da KTORZE M.',
        price: 'R$ 250,00',
        image: 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=2874&auto=format&fit=crop',
        status: 'upcoming'
    }
];
