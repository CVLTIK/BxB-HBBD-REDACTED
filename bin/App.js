import React from 'react';
import { Routes, Route } from 'react-router-dom';
import { ThemeProvider, createTheme } from '@mui/material/styles';
import CssBaseline from '@mui/material/CssBaseline';
import DashboardLayout from './components/DashboardLayout';
import Dashboard from './pages/Dashboard';
import Documentation from './pages/Documentation';
import ServerSetup from './pages/ServerSetup';
import ScriptManager from './pages/ScriptManager';

const theme = createTheme({
  palette: {
    mode: 'light',
    primary: {
      main: '#1976d2',
    },
    secondary: {
      main: '#dc004e',
    },
  },
});

function App() {
  return (
    <ThemeProvider theme={theme}>
      <CssBaseline />
      <DashboardLayout>
        <Routes>
          <Route path="/" element={<Dashboard />} />
          <Route path="/documentation" element={<Documentation />} />
          <Route path="/server-setup" element={<ServerSetup />} />
          <Route path="/script-manager" element={<ScriptManager />} />
        </Routes>
      </DashboardLayout>
    </ThemeProvider>
  );
}

export default App; 