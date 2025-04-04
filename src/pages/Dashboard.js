import React from 'react';
import {
  Box,
  Grid,
  Paper,
  Typography,
  Card,
  CardContent,
  CardHeader,
} from '@mui/material';
import {
  Settings as SettingsIcon,
  Code as CodeIcon,
  Description as DocumentationIcon,
} from '@mui/icons-material';

function Dashboard() {
  return (
    <Box>
      <Typography variant="h4" gutterBottom>
        Welcome to BxB Dashboard
      </Typography>
      
      <Grid container spacing={3}>
        <Grid item xs={12} md={4}>
          <Card>
            <CardHeader
              avatar={<SettingsIcon />}
              title="Server Setup"
              subheader="Configure your server settings"
            />
            <CardContent>
              <Typography variant="body2" color="text.secondary">
                Manage server configurations, environment variables, and system settings.
              </Typography>
            </CardContent>
          </Card>
        </Grid>
        
        <Grid item xs={12} md={4}>
          <Card>
            <CardHeader
              avatar={<CodeIcon />}
              title="Script Manager"
              subheader="Manage your scripts"
            />
            <CardContent>
              <Typography variant="body2" color="text.secondary">
                Add, edit, and manage custom scripts for your WordPress installation.
              </Typography>
            </CardContent>
          </Card>
        </Grid>
        
        <Grid item xs={12} md={4}>
          <Card>
            <CardHeader
              avatar={<DocumentationIcon />}
              title="Documentation"
              subheader="Access guides and help"
            />
            <CardContent>
              <Typography variant="body2" color="text.secondary">
                Find detailed documentation and guides for using the dashboard.
              </Typography>
            </CardContent>
          </Card>
        </Grid>
      </Grid>
    </Box>
  );
}

export default Dashboard; 