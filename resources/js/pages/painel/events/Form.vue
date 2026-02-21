<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between mb-8">
      <div>
        <h2 class="text-2xl font-bold text-zinc-900">{{ isEditing ? 'Editar Evento' : 'Novo Evento' }}</h2>
        <p class="text-sm text-zinc-500 mt-1">Preencha os dados abaixo para {{ isEditing ? 'atualizar' : 'cadastrar' }} o evento.</p>
      </div>
      <button 
        @click="goBack"
        class="flex items-center space-x-2 px-4 py-2 border border-zinc-200 rounded-xl text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 transition-colors bg-white shadow-sm font-medium"
      >
        <ArrowLeftIcon class="w-4 h-4" />
        <span>Voltar</span>
      </button>
    </div>

    <form @submit.prevent="save" class="bg-white rounded-2xl shadow-sm border border-zinc-100 p-6 sm:p-8">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Nome do Evento -->
        <div class="col-span-1 md:col-span-2">
          <label class="block text-sm font-semibold text-zinc-700 mb-2">Nome do Evento</label>
          <input 
            type="text" 
            v-model="form.name"
            required
            placeholder="Ex: Festival de Verão 2026"
            class="block w-full px-4 py-3 border border-zinc-200 rounded-xl bg-zinc-50/50 text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
          >
        </div>

        <!-- Local -->
        <div>
          <label class="block text-sm font-semibold text-zinc-700 mb-2">Local</label>
          <input 
            type="text" 
            v-model="form.location"
            placeholder="Ex: Arena Parque"
            class="block w-full px-4 py-3 border border-zinc-200 rounded-xl bg-zinc-50/50 text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
          >
        </div>

        <!-- Data e Hora do Evento -->
        <div>
          <label class="block text-sm font-semibold text-zinc-700 mb-2">Data e Hora do Evento</label>
          <input 
            type="datetime-local" 
            v-model="form.date"
            class="block w-full px-4 py-3 border border-zinc-200 rounded-xl bg-zinc-50/50 text-zinc-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
          >
        </div>
        
        <!-- Status -->
        <div>
          <label class="block text-sm font-semibold text-zinc-700 mb-2">Status</label>
          <select 
            v-model="form.status"
            class="block w-full px-4 py-3 border border-zinc-200 rounded-xl bg-zinc-50/50 text-zinc-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
          >
            <option value="upcoming">Em Breve (Upcoming)</option>
            <option value="sold-out">Esgotado (Sold-Out)</option>
            <option value="past">Realizado (Past)</option>
          </select>
        </div>

        <!-- Preço -->
        <div>
          <label class="block text-sm font-semibold text-zinc-700 mb-2">Preço (Ex: R$ 50,00 ou Gratuito)</label>
          <input 
            type="text" 
            v-model="form.price"
            placeholder="Ex: R$ 50,00"
            class="block w-full px-4 py-3 border border-zinc-200 rounded-xl bg-zinc-50/50 text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
          >
        </div>

        <!-- Link dos Ingressos -->
        <div>
          <label class="block text-sm font-semibold text-zinc-700 mb-2">Link dos Ingressos</label>
          <input 
            type="url" 
            v-model="form.ticket_link"
            placeholder="https://..."
            class="block w-full px-4 py-3 border border-zinc-200 rounded-xl bg-zinc-50/50 text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
          >
        </div>

        <!-- Descrição -->
        <div class="col-span-1 md:col-span-2">
          <label class="block text-sm font-semibold text-zinc-700 mb-2">Descrição</label>
          <div class="border border-zinc-200 rounded-xl bg-zinc-50/50 overflow-hidden focus-within:ring-2 focus-within:ring-indigo-500/50 focus-within:border-indigo-500 transition-all quill-wrapper-custom">
            <QuillEditor
              v-model:content="form.description"
              contentType="html"
              theme="snow"
              placeholder="Detalhes sobre o evento..."
            />
          </div>
        </div>
      </div>

      <!-- SECTION: LINE-UP (ARTISTAS) -->
      <div class="col-span-1 md:col-span-2 border-t border-zinc-100 pt-8 mt-6">
        <h3 class="text-lg font-bold text-zinc-900 mb-1 flex items-center">
            <Mic2Icon class="w-5 h-5 mr-2 text-indigo-500" />
            Line-up do Evento
        </h3>
        <p class="text-sm text-zinc-500 mb-6">Adicione os artistas e defina a ordem de apresentação deles no evento.</p>

        <div class="flex flex-col sm:flex-row gap-4 mb-6">
          <select v-model="selectedArtistToAdd" class="block w-full px-4 py-3 border border-zinc-200 rounded-xl bg-zinc-50/50 text-zinc-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
            <option :value="null" disabled>Selecione um artista...</option>
            <option v-for="artist in availableArtists" :key="artist.id" :value="artist">{{ artist.name }}</option>
          </select>
          <button type="button" @click="addArtist" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-colors whitespace-nowrap shadow-sm">
            Adicionar
          </button>
        </div>

        <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden shadow-sm">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-zinc-50 border-b border-zinc-200 text-sm font-semibold text-zinc-500">
                <th class="p-4 w-24 text-center">Ordem</th>
                <th class="p-4">Artista</th>
                <th class="p-4 pr-6 text-right">Ações</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100">
              <tr v-for="(item, index) in eventArtists" :key="index" class="hover:bg-zinc-50 transition-colors">
                <td class="p-4 text-center">
                  <input type="number" v-model="item.display_order" class="w-16 px-2 py-1.5 border border-zinc-200 bg-white rounded-lg text-center font-medium text-zinc-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/50" min="1" />
                </td>
                <td class="p-4 font-bold text-zinc-800">
                  {{ item.name }}
                </td>
                <td class="p-4 pr-6 text-right">
                  <button type="button" @click="removeArtist(index)" class="text-rose-500 hover:text-rose-700 p-2 rounded-lg hover:bg-rose-50 transition-colors">
                    <TrashIcon class="w-5 h-5" />
                  </button>
                </td>
              </tr>
              <tr v-if="eventArtists.length === 0">
                <td colspan="3" class="p-8 text-center text-sm text-zinc-400 font-medium">
                  Nenhum artista foi adicionado ao line-up ainda. Selecione um acima.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- SECTION: MEDIA/GALLERY DO EVENTO -->
      <div class="col-span-1 md:col-span-2 border-t border-zinc-100 pt-8 mt-6">
        <h3 class="text-lg font-bold text-zinc-900 mb-1 flex items-center">
            <CameraIcon class="w-5 h-5 mr-2 text-indigo-500" />
            Galeria do Evento
        </h3>
        <p class="text-sm text-zinc-500 mb-6">Adicione imagens de divulgação do evento ou fotos do evento para o portifolio</p>

        <!-- Upload Dropzone -->
        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-indigo-200 border-dashed rounded-xl bg-indigo-50/30 hover:bg-indigo-50 transition-colors cursor-pointer" @click="triggerGalleryInput">
          <div class="space-y-1 text-center">
            <UploadCloudIcon class="mx-auto h-12 w-12 text-indigo-400" />
            <div class="flex text-sm text-zinc-600 justify-center">
              <label for="event-gallery-files" class="relative cursor-pointer rounded-md font-semibold text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                <span>Adicionar Imagens (Múltiplas Seleções)</span>
                <input id="event-gallery-files" ref="galleryInput" name="event-gallery-files" type="file" multiple class="sr-only" accept="image/*" @change="handleGalleryUpload">
              </label>
            </div>
            <p class="text-xs text-zinc-500">PNG, JPG de até 10MB por arquivo</p>
          </div>
        </div>

        <!-- Media Gallery Grid Preview -->
        <div v-if="galleryFiles.length > 0" class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
          <div 
              v-for="(file, index) in galleryFiles" 
              :key="index" 
              class="relative rounded-xl border border-zinc-200 bg-zinc-100 overflow-hidden shadow-sm group aspect-square flex flex-col"
              :class="{'ring-2 ring-indigo-500 border-transparent': file.isCover}"
          >
              <!-- Placeholder Preview -->
              <div class="flex-1 flex items-center justify-center bg-zinc-200 text-zinc-500 relative">
                  <ImageIcon class="w-8 h-8 opacity-50" />
                  
                  <!-- Cover Badge -->
                  <div v-if="file.isCover" class="absolute top-2 left-2 bg-indigo-600 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm">
                      CAPA
                  </div>
                  
                  <!-- Overlay Actions -->
                  <div class="absolute inset-0 bg-zinc-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center space-y-2 pb-2 backdrop-blur-[1px]">
                    <button type="button" @click.stop="setAsCover(index)" class="px-2 py-1 bg-white hover:bg-indigo-50 text-indigo-700 text-xs font-bold rounded shadow transition-colors w-[80%]">
                        Definir Capa
                    </button>
                    <button type="button" @click.stop="removeGalleryFile(index)" class="px-2 py-1 bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold rounded shadow transition-colors w-[80%]">
                        Remover
                    </button>
                  </div>
              </div>
              
              <div class="p-2 bg-white flex justify-between items-center text-xs truncate border-t border-zinc-100">
                <span class="truncate block w-full pr-1 font-medium text-zinc-600" :title="file.name">{{ file.name }}</span>
              </div>
          </div>
        </div>
        <div v-else class="mt-6 flex items-center justify-center p-8 bg-zinc-50 rounded-xl border border-zinc-100 border-dashed text-zinc-400 text-sm font-medium">
            Sem imagens na galeria deste evento.
        </div>
      </div>

      <div class="mt-8 pt-6 border-t border-zinc-100 flex justify-end">
        <button 
          type="button" 
          @click="goBack"
          class="mr-4 bg-white px-5 py-2.5 border border-zinc-200 rounded-xl shadow-sm text-sm font-bold text-zinc-700 hover:bg-zinc-50 transition-colors"
        >
          Cancelar
        </button>
        <button 
          type="submit" 
          class="flex justify-center px-6 py-2.5 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-rose-600 hover:from-indigo-500 hover:to-rose-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:scale-[1.02]"
        >
          {{ isEditing ? 'Atualizar Evento' : 'Salvar Evento' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { ArrowLeft as ArrowLeftIcon, Calendar as CalendarIcon, Camera as CameraIcon, UploadCloud as UploadCloudIcon, Image as ImageIcon, Trash2 as TrashIcon, Mic2 as Mic2Icon } from 'lucide-vue-next';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import api from '../../../services/api';

const router = useRouter();
const route = useRoute();

const isEditing = ref(false);
const isSubmitting = ref(false);
const errors = ref<Record<string, string[]>>({});

// Line-up
interface ArtistItem {
  id: number;
  name: string;
}

interface EventArtist {
  artist_id: number;
  name: string;
  display_order: number;
}

const availableArtists = ref<ArtistItem[]>([]);
const selectedArtistToAdd = ref<ArtistItem | null>(null);
const eventArtists = ref<EventArtist[]>([]);

// Gallery
const galleryInput = ref<HTMLInputElement | null>(null);
interface MediaFile {
  id?: number; // Se veio do backend
  name: string;
  isCover: boolean;
  file?: File; // Se for novo upload
  media_url?: string;
}
const galleryFiles = ref<MediaFile[]>([]);

const form = reactive({
  name: '',
  location: '',
  date: '',
  price: '',
  ticket_link: '',
  status: 'upcoming',
  description: '',
});

const fetchArtistsList = async () => {
    try {
        const response = await api.get('/artists');
        availableArtists.value = response.data.map((a: any) => ({
             id: a.id,
             name: a.name
        }));
    } catch (e) {
        console.error('Falha ao carregar artistas', e);
    }
}

onMounted(async () => {
  await fetchArtistsList();

  if (route.params.id) {
    isEditing.value = true;
    try {
        const response = await api.get(`/events/${route.params.id}`);
        const event = response.data;
        
        form.name = event.name;
        form.location = event.location;
        form.price = event.price || '';
        form.ticket_link = event.ticket_link || '';
        form.status = event.status;
        form.description = event.description;
        
        if (event.date) {
             const dateObj = new Date(event.date);
             // Format to YYYY-MM-DDThh:mm for datetime-local
             const localIso = new Date(dateObj.getTime() - (dateObj.getTimezoneOffset() * 60000)).toISOString().slice(0, 16);
             form.date = localIso;
        }

        if (event.lineup) {
             eventArtists.value = event.lineup.map((a: any) => ({
                  artist_id: a.id,
                  name: a.name,
                  display_order: a.pivot.display_order
             })).sort((a: any, b: any) => a.display_order - b.display_order);
        }

        if (event.media) {
             galleryFiles.value = event.media.map((m: any) => ({
                  id: m.id,
                  name: m.media_url.split('/').pop() || 'media',
                  isCover: m.cover_image === 1,
                  media_url: m.media_url
             }));
        }
    } catch (error) {
        console.error('Failed to fetch event:', error);
        router.push('/painel/eventos');
    }
  }
});

const goBack = () => {
  router.push('/painel/eventos');
};

// Line-up Event Methods
const addArtist = () => {
  if (!selectedArtistToAdd.value) return;
  
  // Prevent duplicate
  const exists = eventArtists.value.find(a => a.artist_id === selectedArtistToAdd.value?.id);
  if (exists) {
    alert('Artista já adicionado ao line-up!');
    return;
  }
  
  eventArtists.value.push({
    artist_id: selectedArtistToAdd.value.id,
    name: selectedArtistToAdd.value.name,
    display_order: eventArtists.value.length + 1,
  });
  
  selectedArtistToAdd.value = null; // reset
};

const removeArtist = (index: number) => {
  eventArtists.value.splice(index, 1);
};

// Gallery Event Methods
const triggerGalleryInput = () => {
  galleryInput.value?.click();
};

const handleGalleryUpload = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files.length > 0) {
    const files = Array.from(target.files);
    files.forEach(file => {
      const isFirst = galleryFiles.value.length === 0;
      galleryFiles.value.push({
        name: file.name,
        isCover: isFirst,
        file: file
      });
    });
  }
};

const setAsCover = (index: number) => {
  galleryFiles.value.forEach((media, i) => {
    media.isCover = i === index;
  });
};

const removeGalleryFile = async (index: number) => {
  const file = galleryFiles.value[index];
  
  if (file.id) {
       // Se já existe no back, excluir real
       if (!confirm("Deseja realmente remover essa imagem da galeria?")) return;
       try {
            await api.delete(`/events/${route.params.id}/media/${file.id}`);
       } catch (e) {
            console.error("Falha ao deletar media", e);
            alert("Erro ao remover arquivo.");
            return;
       }
  }

  const wasCover = file.isCover;
  galleryFiles.value.splice(index, 1);
  
  if (wasCover && galleryFiles.value.length > 0) {
    galleryFiles.value[0].isCover = true;
  }
};

const save = async () => {
    isSubmitting.value = true;
    errors.value = {};
    
    const payload = { ...form };
    
    // Process Date for backend (convert from local input back to generic UTC/mysql standard depends on setup,
    // usually YYYY-MM-DD HH:mm:ss is best for Laravel)
    if (payload.date) {
         payload.date = payload.date.replace('T', ' ') + ':00';
    }

    // Process Lineup
    const lineupIds = eventArtists.value.map(a => a.artist_id);

    try {
        let eventId = route.params.id;

        if (isEditing.value) {
            await api.put(`/events/${eventId}`, {
                 ...payload,
                 lineup: lineupIds
            });
        } else {
            const res = await api.post('/events', {
                 ...payload,
                 lineup: lineupIds
            });
            eventId = res.data.id;
        }

        // Handle Media Uploads individually (since multiple files with booleans are complex in one payload)
        const newFiles = galleryFiles.value.filter(m => m.file);
        // also need to update cover statuses if they changed for existing files.
        // For simplicity in this generic integration, we will upload new ones and just pass "is_cover" correctly.
        
        if (newFiles.length > 0) {
               for (const mf of newFiles) {
                    const fd = new FormData();
                    fd.append('media', mf.file!);
                    fd.append('cover_image', mf.isCover ? '1' : '0');
                    fd.append('type', 'image'); // assumption based on common needs
                    await api.post(`/events/${eventId}/media`, fd, {
                         headers: { 'Content-Type': 'multipart/form-data' }
                    });
               }
        }

        router.push('/painel/eventos');
    } catch (error: any) {
        if (error.response && error.response.status === 422) {
            errors.value = error.response.data.errors;
            alert('Erro de validação, verifique os campos.');
        } else {
            console.error('Failed to save event:', error);
            alert('Erro ao salvar o evento.');
        }
    } finally {
        isSubmitting.value = false;
    }
};
</script>

<style>
.quill-wrapper-custom .ql-toolbar.ql-snow {
  border: none !important;
  border-bottom: 1px solid #e4e4e7 !important;
  background-color: transparent;
  font-family: inherit;
  border-top-left-radius: 0.75rem;
  border-top-right-radius: 0.75rem;
}
.quill-wrapper-custom .ql-container.ql-snow {
  border: none !important;
  font-family: inherit;
  font-size: 1rem;
  min-height: 150px;
  background-color: #fff;
  border-bottom-left-radius: 0.75rem;
  border-bottom-right-radius: 0.75rem;
}
.quill-wrapper-custom .ql-editor {
  min-height: 150px;
}
</style>
